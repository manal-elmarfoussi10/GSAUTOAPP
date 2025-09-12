<?php
// app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Show the contact form (tenant side).
     */
    public function index()
    {
        return view('contact.contact'); // was contact.index
    }

    /**
     * Handle submission and persist the contact message.
     */
    public function send(Request $request)
    {
        // Basic validation
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // Attach company_id when the sender is authenticated (tenant user)
        $data['company_id'] = Auth::check() ? (Auth::user()->company_id ?? null) : null;

        // Persist
        $contact = Contact::create($data);

        /**
         * (Optional) Notify superadmins by email/notification
         * Uncomment/replace with your preferred notification logic.
         *
         * use Illuminate\Support\Facades\Notification;
         * use App\Notifications\NewContactMessage; // create if you want
         *
         * $superadmins = User::where('role', 'superadmin')->get();
         * Notification::send($superadmins, new NewContactMessage($contact));
         */

        return back()->with('success', 'Message envoyé. Merci, nous revenons vers vous rapidement.');
    }
}