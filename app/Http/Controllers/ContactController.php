<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.contact');
    }

    public function send(Request $request)
    {
        // Validation
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        // Enregistrement en base
        Contact::create($data);

        // Envoi email
        Mail::raw($data['message'], function ($message) use ($data) {
            $message->to('votre_email@tonsite.com')  // <-- Remplace par ton adresse réelle
                    ->subject("Message de {$data['name']}")
                    ->replyTo($data['email']);
        });

        return redirect()->back()->with('success', 'Votre message a bien été envoyé. Merci !');
    }
}
