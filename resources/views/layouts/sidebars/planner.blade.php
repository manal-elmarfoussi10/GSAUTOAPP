<div class="p-3 border-b border-gray-200 flex justify-left">
    <img src="{{ asset('images/GS.png') }}" alt="GG AUTO Logo" class="h-20" />
</div>

<nav class="flex-1 overflow-y-auto text-sm text-gray-700">
    <ul class="space-y-1 px-2 py-4">
       
        <li>
            <a href="{{ route('devis.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('devis.*') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="file-text" class="w-4 h-4"></i> Devis
            </a>
        </li>
        <li>
            <a href="{{ route('factures.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('factures.*') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="file" class="w-4 h-4"></i> Factures
            </a>
        </li>
        <li>
            <a href="{{ route('avoirs.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('avoirs.*') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Avoirs
            </a>
        </li>
        <li>
            <a href="{{ route('depenses.index', ['facture' => 1]) }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('paiement.*') || request()->routeIs('paiements.*') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="credit-card" class="w-4 h-4"></i> Dépenses / achats
            </a>
        </li>
        <li>
            <a href="{{ route('bons-de-commande.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('bons-de-commande.*') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="package" class="w-4 h-4"></i> Bons de commandes
            </a>
        </li>
        <li><a href="{{ route('fournisseurs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100">
            <i data-lucide="truck" class="w-4 h-4"></i> Fournisseurs</a></li>
        <li><a href="{{ route('produits.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100">
            <i data-lucide="clipboard-list" class="w-4 h-4"></i> Produits</a></li>
       
    </ul>


    <ul class="space-y-1 px-2 py-2 border-t border-gray-200">
        <li><a href="" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100"><i data-lucide="settings" class="w-4 h-4"></i> Paramètres</a></li>
        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100"><i data-lucide="log-out" class="w-4 h-4"></i> Déconnexion</a></li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </ul>
</nav>
