<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VirementRequest;
use App\Models\User;

class UnitController extends Controller
{
    public function showPurchaseForm()
    {
        return view('units.purchase');
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1',
            'payment_method' => 'required|in:stripe,virement',
            'virement_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $qty = $request->input('quantity');
        $subtotal = $qty * 10;
        $tva = $subtotal * 0.2;
        $total = $subtotal + $tva;

        // ⚠️ Temporarily use fake user (replace ID 1 with any existing user)
        $user = auth()->user() ?? User::find(1);

        if (!$user) {
            return redirect()->back()->with('error', 'Aucun utilisateur disponible.');
        }

        if ($request->payment_method === 'stripe') {
            $user->increment('units', $qty);

            return redirect()->back()->with('success', 'Paiement effectué avec succès. Unités ajoutées à votre compte.');
        }

        if ($request->payment_method === 'virement') {
            $path = $request->hasFile('virement_proof')
                ? $request->file('virement_proof')->store('virements', 'public')
                : null;

            VirementRequest::create([
                'user_id' => $user->id,
                'quantity' => $qty,
                'proof_path' => $path,
                'status' => 'pending',
            ]);

            return redirect()->back()->with('success', 'Votre demande de virement a été envoyée. Notre équipe vérifiera le reçu et ajoutera les unités sous peu.');
        }

        return redirect()->back()->with('error', 'Méthode de paiement invalide.');
    }
}