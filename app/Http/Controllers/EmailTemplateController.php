<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::latest()->get();
        $activeTemplate = $templates->first();
        return view('email-templates.index', compact('templates', 'activeTemplate'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'file' => 'nullable|file',
            'file_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('email_attachments');
        }

        EmailTemplate::create($data);

        return redirect()->route('email-templates.index')->with('success', 'Modèle ajouté.');
    }

    public function show(EmailTemplate $emailTemplate)
    {
        $templates = EmailTemplate::latest()->get();
        return view('email-templates.index', [
            'templates' => $templates,
            'activeTemplate' => $emailTemplate
        ]);
    }

    public function inbox()
{
    $templates = EmailTemplate::latest()->get();
    return view('email-templates.inbox', compact('templates'));
}
}
