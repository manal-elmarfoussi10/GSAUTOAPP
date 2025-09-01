<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Intervention;

class DashboardPoseurController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isRole('poseur')) {
            abort(403);
        }

        $interventions = Intervention::where('poseur_id', Auth::id())
                        ->with('client')
                        ->get();

        return view('dashboardposeur', compact('interventions'));
    }

    public function dossiers()
    {
        if (!Auth::user()->isRole('poseur')) {
            abort(403);
        }

        $interventions = Intervention::where('poseur_id', Auth::id())
                        ->with('client')
                        ->get();

        return view('dossiersposeur', compact('interventions')); // ✅ ton vrai fichier
    }
public function ajouterCommentaire(Request $request, $id)
{
    $request->validate([
        'commentaire' => 'nullable|string',
        'photo' => 'nullable|image|max:2048', // 2MB max
    ]);

    $intervention = \App\Models\Intervention::findOrFail($id);

    // sécurité : seul le poseur de l'intervention peut modifier
    if ($intervention->poseur_id !== auth()->id()) {
        abort(403);
    }

    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('interventions/photos');
        $intervention->photo = $path;
    }

    $intervention->commentaire = $request->input('commentaire');
    $intervention->save();

    return back()->with('success', 'Commentaire ajouté avec succès.');
}


}


