<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ConversationThread;
use App\Models\Email;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ConversationController extends Controller
{
    public function show(Client $client)
    {
        // Retrieve all emails in this client's thread
        $emails = $client->emails()
            ->with(['senderUser', 'receiverUser', 'replies.senderUser'])
            ->orderBy('created_at')
            ->get();

        // Only admins and client-service roles
        $allowedRoles = [
            User::ROLE_ADMIN,
            User::ROLE_CLIENT_SERVICE,
            User::ROLE_CLIENT_LIMITED,
            User::ROLE_PLANNER,
            User::ROLE_SUPERADMIN,
        ];

        $users = User::where('company_id', Auth::user()->company_id)
                     ->whereIn('role', $allowedRoles)
                     ->get();

        return view('clients.show', compact('client', 'emails', 'users'));
    }
    public function store(Request $request, Client $client)
    {
        // Only admins and client-service roles
        $allowedRoles = [
            User::ROLE_ADMIN,
            User::ROLE_CLIENT_SERVICE,
        ];

        $request->validate([
            'receiver' => [
                'required',
                Rule::exists('users', 'id')->where(function($query) use ($allowedRoles) {
                    $query->whereIn('role', $allowedRoles)
                          ->where('company_id', Auth::user()->company_id);
                }),
            ],
            'subject'  => 'required|string|max:255',
            'content'  => 'required|string',
            'file'     => 'nullable|file|max:2048',
        ]);

        // Create conversation thread
        $thread = ConversationThread::create([
            'client_id'  => $client->id,
            'company_id' => Auth::user()->company_id,
            'subject'    => $request->subject,
            'creator_id' => Auth::id(),
        ]);

        // Create the initial email
        $email = new Email([
            'client_id'       => $client->id,
            'company_id'      => Auth::user()->company_id,
            'sender_id'       => Auth::id(),
            'receiver_id'     => $request->receiver,
            'subject'         => $request->subject,
            'content'         => $request->content,
            'folder'          => 'sent',
        ]);

        // Associate the email with the thread
        $thread->emails()->save($email);

        // File handling
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('conversations', 'public');
            $email->file_path = $path;
            $email->file_name = $request->file('file')->getClientOriginalName();
            $email->save();
        }

        return back()->with('success', 'Conversation started!');
    }

    // app/Http/Controllers/ConversationController.php
    public function reply(Request $request, Email $email)
    {
        // Only admins and client-service roles
        $allowedRoles = [
            User::ROLE_ADMIN,
            User::ROLE_CLIENT_SERVICE,
        ];

        $rules = [
            'content' => 'required|string',
            'file'    => 'nullable|file|max:2048',
        ];
        // If receiver is a parameter in the request, validate similarly
        if ($request->has('receiver')) {
            $rules['receiver'] = [
                'required',
                Rule::exists('users', 'id')->where(function($query) use ($allowedRoles) {
                    $query->whereIn('role', $allowedRoles)
                          ->where('company_id', Auth::user()->company_id);
                }),
            ];
        }
        $request->validate($rules);

        $receiverId = $request->has('receiver') ? $request->receiver : $email->sender_id;

        $reply = Reply::create([
            'email_id'        => $email->id,
            'conversation_id' => $email->thread_id,    // point at the correct column!
            'sender_id'       => Auth::id(),
            'receiver_id'     => $receiverId,
            'content'         => $request->content,
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('conversations', 'public');
            $reply->file_path = $path;
            $reply->file_name = $request->file('file')->getClientOriginalName();
            $reply->save();
        }

        return back()->with('success', 'Reply sent!');
    }

     /**
     * Fetch the latest messages HTML for live updates.
     */
    public function fetch(Client $client)
    {
        $emails = $client->emails()
                         ->with(['senderUser','receiverUser','replies.senderUser'])
                         ->orderBy('created_at')
                         ->get();

        // Return just the rendered messages list
        return view('clients.partials._messages', compact('emails'));
    }


    public function destroyThread(ConversationThread $thread)
    {
        // Remove all emails & replies under this thread
        $thread->emails()->each->replies()->delete();
        $thread->emails()->delete();
        $thread->delete();

        return back()->with('success', 'Conversation deleted.');
    }

    public function download(Reply $reply)
    {
        $fullPath = storage_path('/storage/app/public/' . $reply->file_path);

        if (! file_exists($fullPath)) {
            abort(404);
        }

        return response()->download($fullPath, $reply->file_name);
    }

}
