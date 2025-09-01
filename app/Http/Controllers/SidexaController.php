<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sidexa;

class SidexaController extends Controller
{
    public function index()
    {
        $sidexas = Sidexa::latest()->get();
        return view('sidexa.index', compact('sidexas'));
    }

    public function create()
    {
        return view('sidexa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'plate' => 'required',
            'glass_type' => 'required',
        ]);

        Sidexa::create($request->all());

        return redirect()->route('sidexa.index')->with('success', 'Chiffrage enregistré avec succès.');
    }
}