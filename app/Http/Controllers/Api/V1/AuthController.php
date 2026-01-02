<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\RefreshTokenRequest;
use Laravel\Passport\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Laravel\Passport\Http\Controllers\AccessTokenController;


class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $loginType = $request->getLoginType();

    // Validate login type
    if ($loginType === 'invalid') {
        return response()->json([
            'success' => false,
            'status'  => 422,
            'message' => 'Invalid login format. Please use a valid email or username (4-16 characters).'
        ], 422);
    }

    $loginField = $request->getLoginField();
    $credentials = [
        $loginField => $request->input('username'),
        'password' => $request->input('password')
    ];

    // Attempt authentication
    if (!Auth::attempt($credentials)) {
        return response()->json([
            'success' => false,
            'status'  => 401,
            'message' => 'Invalid credentials.'
        ], 401);
    }
        // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();


            $psrRequest = new ServerRequest(
                    'POST',
                    $request->fullUrl(),
                    [],
                    null,
                    '1.1',
                    $request->server->all()
                );
            $psrRequest = $psrRequest->withParsedBody([
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.secret'),
                'username' => $user->email,
                'password' => $request->password,
                'scope' => '',
            ]);

            // Create a PSR-7 Response object
            $psrResponse = new Psr7Response();

            // Call Passport's AccessTokenController
            $controller = app(AccessTokenController::class);

            $response = $controller->issueToken($psrRequest, $psrResponse);

            // Return JSON output to client
            $data = json_decode($response->getContent(), true);

            return response()->json($data);

            // $response = Http::post(env('APP_URL') . '/oauth/token', [
            //     'grant_type' => 'password',
            //     'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            //     'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
            //     'username' => $request->email,
            //     'password' => $request->password,
            //     'scope' => '',
            // ]);

            // $user['token'] = $response->json();

            // return response()->json([
            //     'success' => true,
            //     'statusCode' => 200,
            //     'message' => 'User has been logged successfully.',
            //     'data' => $user,
            // ], 200);
        // } else {
        //     return response()->json([
        //         'success' => true,
        //         'statusCode' => 401,
        //         'message' => 'Unauthorized.',
        //         'errors' => 'Unauthorized',
        //     ], 401);
        // }

    }

    public function me(): JsonResponse
    {

        $user = auth()->user();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Authenticated use info.',
            'data' => $user,
        ], 200);
    }

    public function refreshToken(Request $request): JsonResponse
    {
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        // Build PSR-7 Request
        $psrRequest = new ServerRequest(
            'POST',
            url('/oauth/token'),
            [],
            null,
            '1.1'
        );

        $psrRequest = $psrRequest->withParsedBody([
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
            'scope' => '',
        ]);

        // PSR-7 Response placeholder
        $psrResponse = new Psr7Response();

        // Issue new token using Passport
        $response = app(AccessTokenController::class)->issueToken($psrRequest, $psrResponse);

        // Symfony response → decode using getContent()
        $data = json_decode($response->getContent(), true);

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Token refreshed.',
            'data' => $data
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
