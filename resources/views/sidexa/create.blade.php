@extends('layout')

@section('content')
<div class="max-w-xl mx-auto px-6 py-8 bg-white shadow rounded-lg">
    <h2 class="text-2xl font-bold text-orange-600 mb-6">Chiffrage Sidexa</h2>

    <form method="POST" action="{{ route('sidexa.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-medium mb-1">Nom (ou Société)</label>
            <input type="text" name="name" class="w-full border rounded px-4 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Plaque d'immatriculation</label>
            <input type="text" name="plate" class="w-full border rounded px-4 py-2" required>
        </div>

        <div class="mb-6">
            <label for="vitrage_type" class="block text-sm font-medium text-gray-700">Type de vitrage</label>
            <select name="vitrage_type" id="vitrage_type" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500">
                <option value="">-- Sélectionnez --</option>
                <option value="Pare-brise">Pare-brise</option>
                <option value="Lunette arrière">Lunette arrière</option>
                <option value="Vitre porte latérale avant - [Gauche]">Vitre porte latérale avant - [Gauche]</option>
                <option value="Vitre porte latérale avant - [Droite]">Vitre porte latérale avant - [Droite]</option>
                <option value="Vitre porte latérale arrière - [Gauche]">Vitre porte latérale arrière - [Gauche]</option>
                <option value="Vitre porte latérale arrière - [Droite]">Vitre porte latérale arrière - [Droite]</option>
                <option value="Pavillon panoramique">Pavillon panoramique</option>
                <option value="Vitre triangulaire avant - [Gauche]">Vitre triangulaire avant - [Gauche]</option>
                <option value="Vitre triangulaire avant - [Droite]">Vitre triangulaire avant - [Droite]</option>
                <option value="Vitre triangulaire porte latérale avant - [Gauche]">Vitre triangulaire porte latérale avant - [Gauche]</option>
                <option value="Vitre triangulaire porte latérale avant - [Droite]">Vitre triangulaire porte latérale avant - [Droite]</option>
                <option value="Vitre arrière porte latérale arrière - [Gauche]">Vitre arrière porte latérale arrière - [Gauche]</option>
                <option value="Vitre arrière porte latérale arrière - [Droite]">Vitre arrière porte latérale arrière - [Droite]</option>
                <option value="Lunette arrière inférieure">Lunette arrière inférieure</option>
                <option value="Lunette arrière supérieure">Lunette arrière supérieure</option>
                <option value="Vitre custode arrière - [Gauche]">Vitre custode arrière - [Gauche]</option>
                <option value="Vitre custode arrière - [Droite]">Vitre custode arrière - [Droite]</option>
                <option value="Vitre arrière custode arrière - [Gauche]">Vitre arrière custode arrière - [Gauche]</option>
                <option value="Vitre arrière custode arrière - [Droite]">Vitre arrière custode arrière - [Droite]</option>
                <option value="Vitres portes chargement arrière (Utilitaire) - [Gauche]">Vitres portes chargement arrière (Utilitaire) - [Gauche]</option>
                <option value="Vitres portes chargement arrière (Utilitaire) - [Droite]">Vitres portes chargement arrière (Utilitaire) - [Droite]</option>
                <option value="Ensemble vitre panneau latéral - [Gauche]">Ensemble vitre panneau latéral - [Gauche]</option>
                <option value="Ensemble vitre panneau latéral - [Droite]">Ensemble vitre panneau latéral - [Droite]</option>
                <option value="Vitre avant panneau latéral - [Gauche]">Vitre avant panneau latéral - [Gauche]</option>
                <option value="Vitre avant panneau latéral - [Droite]">Vitre avant panneau latéral - [Droite]</option>
                <option value="Vitre coulissante avant panneau latéral - [Gauche]">Vitre coulissante avant panneau latéral - [Gauche]</option>
                <option value="Vitre coulissante avant panneau latéral - [Droite]">Vitre coulissante avant panneau latéral - [Droite]</option>
                <option value="Vitre centrale panneau latéral - [Gauche]">Vitre centrale panneau latéral - [Gauche]</option>
                <option value="Vitre centrale panneau latéral - [Droite]">Vitre centrale panneau latéral - [Droite]</option>
                <option value="Vitre coulissante centrale panneau latéral - [Gauche]">Vitre coulissante centrale panneau latéral - [Gauche]</option>
                <option value="Vitre coulissante centrale panneau latéral - [Droite]">Vitre coulissante centrale panneau latéral - [Droite]</option>
                <option value="Vitre arrière panneau latéral - [Gauche]">Vitre arrière panneau latéral - [Gauche]</option>
                <option value="Vitre arrière panneau latéral - [Droite]">Vitre arrière panneau latéral - [Droite]</option>
                <option value="Vitre coulissante arrière panneau latéral - [Gauche]">Vitre coulissante arrière panneau latéral - [Gauche]</option>
                <option value="Vitre coulissante arrière panneau latéral - [Droite]">Vitre coulissante arrière panneau latéral - [Droite]</option>
                <option value="Vitre avant de panneau latéral arrière - [Gauche]">Vitre avant de panneau latéral arrière - [Gauche]</option>
                <option value="Vitre avant de panneau latéral arrière - [Droite]">Vitre avant de panneau latéral arrière - [Droite]</option>
                <option value="Vitre arrière de panneau latéral arrière - [Gauche]">Vitre arrière de panneau latéral arrière - [Gauche]</option>
                <option value="Vitre arrière de panneau latéral arrière - [Droite]">Vitre arrière de panneau latéral arrière - [Droite]</option>
                <option value="Vitre rallonge de panneau latéral arrière - [Gauche]">Vitre rallonge de panneau latéral arrière - [Gauche]</option>
                <option value="Vitre rallonge de panneau latéral arrière - [Droite]">Vitre rallonge de panneau latéral arrière - [Droite]</option>
                <option value="Vitre supérieure panneau latéral - [Gauche]">Vitre supérieure panneau latéral - [Gauche]</option>
                <option value="Vitre supérieure panneau latéral - [Droite]">Vitre supérieure panneau latéral - [Droite]</option>
                <option value="Pavillon avant – [Gauche]">Pavillon avant – [Gauche]</option>
                <option value="Pavillon avant – [Droite]">Pavillon avant – [Droite]</option>
                <option value="Pavillon arrière – [Gauche]">Pavillon arrière – [Gauche]</option>
                <option value="Pavillon arrière – [Droite]">Pavillon arrière – [Droite]</option>
                <option value="Toit ouvrant avant vitré (complet)">Toit ouvrant avant vitré (complet)</option>
                <option value="Vitre toit ouvrant central">Vitre toit ouvrant central</option>
                <option value="Toit ouvrant arrière vitré (complet)">Toit ouvrant arrière vitré (complet)</option>
                <option value="Vitre toit ouvrant arrière">Vitre toit ouvrant arrière</option>
                <option value="Toit ouvrant complet à lamelles">Toit ouvrant complet à lamelles</option>
                <option value="Lamelle 1 de toit ouvrant">Lamelle 1 de toit ouvrant</option>
                <option value="Lamelle 2 de toit ouvrant">Lamelle 2 de toit ouvrant</option>
                <option value="Lamelle 3 de toit ouvrant">Lamelle 3 de toit ouvrant</option>
                <option value="Lamelle 4 de toit ouvrant">Lamelle 4 de toit ouvrant</option>
                <option value="Lamelle 5 de toit ouvrant">Lamelle 5 de toit ouvrant</option>
                <option value="Phare avant (complet) - [Gauche]">Phare avant (complet) - [Gauche]</option>
                <option value="Phare avant (complet) - [Droite]">Phare avant (complet) - [Droite]</option>
                <option value="Phare avant (extérieur) - [Gauche]">Phare avant (extérieur) - [Gauche]</option>
                <option value="Phare avant (extérieur) - [Droite]">Phare avant (extérieur) - [Droite]</option>
                <option value="Feu diurne (extérieur) - [Gauche]">Feu diurne (extérieur) - [Gauche]</option>
                <option value="Feu diurne (extérieur) - [Droite]">Feu diurne (extérieur) - [Droite]</option>
                <option value="Vitre phare avant – [Gauche]">Vitre phare avant – [Gauche]</option>
                <option value="Vitre phare avant – [Droite]">Vitre phare avant – [Droite]</option>
                <option value="Phare antibrouillard avant (complet) – [Gauche]">Phare antibrouillard avant (complet) – [Gauche]</option>
                <option value="Phare antibrouillard avant (complet) – [Droite]">Phare antibrouillard avant (complet) – [Droite]</option>
            </select>
        </div>

        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded">
            Faire le chiffrage Sidexa
        </button>
    </form>
</div>
@endsection