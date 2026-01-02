<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Requests\ContactMessageRequest;

class ContactMessageController extends Controller
{
    /**
     * Generate a token for form submission
     */
    public function presend(Request $request): JsonResponse
    {
        // Rate limiting: max 5 requests per minute per IP
        $executed = RateLimiter::attempt(
            'presend-contact:' . request()->ip(),
            $perMinute = 5,
            function() {
            }
        );

        if (!$executed) {
            return response()->json([
                'message' => 'Too many attempts. Please try again in a minute.'
            ], 429);
        }

        $token = ContactMessage::generateToken();

        // Create a temporary contact record with just the token
        $contact = ContactMessage::create([
            'token' => $token,
            'ip' => $request->ip()
        ]);

        return response()->json([
            'token' => $token,
            'message' => 'Token generated successfully'
        ]);
    }

    /**
     * Process contact form submission
     */
    public function store(ContactMessageRequest $request): JsonResponse
    {
        // Rate limiting for form submission
        $executed = RateLimiter::attempt(
            'contact-submission:' . $request->ip(),
            $perMinute = 3,
            function() {}
        );

        if (!$executed) {
            return response()->json([
                'message' => 'Too many submissions. Please try again in a minute.'
            ], 429);
        }

        try {
            // Find the temporary contact by token
            $contact = ContactMessage::where('token', $request->token)->firstOrFail();

            // Update with actual form data
            $contact->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message,
            ]);

            // Here you can add email notification, etc.
            // Mail::to(config('mail.from.address'))->send(new ContactMessageFormSubmitted($contact));

            Log::info('CONTACT_MESSAGE', [
                'contact_id' => $contact->id,
                'email' => $contact->email,
                'ip' => $request->ip()
            ]);

            $data = $request->only(['name', 'email', 'phone', 'message']);

            // clean user input (strip HTML, trim, remove extra spaces)
            $clean = fn($value) => trim(
                preg_replace('/\s+/', ' ', strip_tags($value))
            );

            $name    = $clean($data['name']);
            $email   = $clean($data['email']);
            $phone   = $clean($data['phone']);
            $message = $clean($data['message']);
            $ip      = $request->ip(); // user IP

            // Send to Telegram only if credentials are configured
            $botToken = config('services.telegram.bot_token');
            $chatId = config('services.telegram.chat_id');

            if ($botToken && $chatId) {
                // Prepare formatted Telegram message
                $telegramMessage = <<<TXT
                📩 New Form Submission
                👤 Name: {$name}
                📧 Email: {$email}
                📱 Phone: {$phone}
                🌐 IP: {$ip}
                💬 Message: {$message}
                TXT;

                // send to Telegram after the request response is returned
                defer(function () use ($telegramMessage, $botToken, $chatId) {
                    Http::post(
                        "https://api.telegram.org/bot{$botToken}/sendMessage",
                        [
                            'chat_id' => $chatId,
                            'text'    => $telegramMessage,
                        ]
                    );
                });
            }

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully! We will get back to you soon.',
                'contact_id' => $contact->id
            ], 200);

        } catch (\Exception $e) {
            Log::error('ContactMessage form submission failed', [
                'error' => $e->getMessage(),
                'token' => $request->token,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'message' => 'Failed to send message. Please try again.'
            ], 500);
        }
    }

    /**
     * Get recent contacts (for admin panel)
     */
    public function index(): JsonResponse
    {
        $contacts = ContactMessage::latest()
            ->take(50)
            ->get()
            ->makeHidden('token');

        return response()->json($contacts);
    }
}
