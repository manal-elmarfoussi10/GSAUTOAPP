<?php

// app/Http/Controllers/Superadmin/ProductController.php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Produit::query()
            ->whereNull('company_id'); // global products

        if ($s = trim($request->get('q', ''))) {
            $query->where(function ($q) use ($s) {
                $q->where('nom', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('categorie', 'like', "%{$s}%");
            });
        }

        // 👇 name this $produits to match the blade
        $produits = $query
            ->orderBy('nom')
            ->paginate(25)
            ->withQueryString();

        // IMPORTANT: view path matches your files: resources/views/superadmin/products/index.blade.php
        return view('superadmin.products.index', compact('produits'));
    }

    public function create()
    {
        abort_unless(auth()->user()?->role === 'superadmin', 403);
        return view('superadmin.products.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()?->role === 'superadmin', 403);

        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'prix_ht' => 'required|numeric',
            'montant_tva' => 'required|numeric',
            'categorie' => 'nullable|string|max:100',
            'actif' => 'boolean'
        ]);

        // Superadmin: company_id stays NULL (global product)
        Produit::create($data);

        return redirect()->route('superadmin.products.index')->with('success', 'Produit global ajouté.');
    }
// app/Http/Controllers/SuperAdmin/ProductController.php

public function edit(\App\Models\Produit $product)
{
    return view('superadmin.products.edit', compact('product'));
}

public function update(Request $request, \App\Models\Produit $product)
{
    $data = $request->validate([
        'nom'         => ['required','string','max:255'],
        'code'        => ['nullable','string','max:100'],
        'description' => ['nullable','string','max:2000'],
        'prix_ht'     => ['required','numeric','min:0'],
        'taux_tva'    => ['nullable','numeric','min:0','max:100'],
        'categorie'   => ['nullable','string','max:255'],
        'actif'       => ['boolean'],
    ]);

    // derive montant_tva if you store it
    $data['montant_tva'] = isset($data['taux_tva'])
        ? round(($data['prix_ht'] * ($data['taux_tva'] / 100)), 2)
        : 0;

    $product->update($data);

    return redirect()
        ->route('superadmin.products.index')
        ->with('success', 'Produit mis à jour.');
}

public function destroy(\App\Models\Produit $product)
{
    $product->delete();

    return redirect()
        ->route('superadmin.products.index')
        ->with('success', 'Produit supprimé.');
}
}
