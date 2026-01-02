<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;
        $query = ContactMessage::latest();
        dd(Auth::user()->email);
        if(Auth::user()->is_demo){
            $query->where('email', Auth::user()->email)->where('id', '<', 10);
        }

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%")
                  ->orWhere('email', 'LIKE', "%$keyword%")
                  ->orWhere('message', 'LIKE', "%$keyword%");
            });
        }

        $messages = $query->paginate($perPage);

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        if(Auth::user()->is_demo){
            $message->where('email', Auth::user()->email)->where('id', '<', 10);
        }
        return view('admin.contact-messages.show', compact('message'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_review,completed,spam',
        ]);

        $message = ContactMessage::findOrFail($id);
        $message->update(['status' => $request->status]);

        return redirect()->route('admin.contact-messages.index')->with('flash_message', 'Message status updated!');
    }

    public function destroy($id)
    {
        ContactMessage::destroy($id);
        return redirect()->route('admin.contact-messages.index')->with('flash_message', 'Message deleted!');
    }
}
