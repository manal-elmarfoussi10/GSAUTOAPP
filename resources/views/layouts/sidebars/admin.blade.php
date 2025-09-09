<div class="p-3 border-b border-gray-200 flex justify-left">
    <img src="{{ asset('images/GS.png') }}" alt="GG AUTO Logo" class="h-20" />
</div>

<nav class="flex-1 overflow-y-auto text-sm text-gray-700">
    <ul class="space-y-1 px-2 py-4">
        <li>
            <a href="{{ route('clients.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('clients.*') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="users" class="w-4 h-4"></i> Gestion dossiers
            </a>
        </li>
        <li>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Tableau de bord
            </a>
        </li>
        <li>
            <a href="{{ route('clients.create') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('clients.create') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Nouveau client
            </a>
        </li>
        <li>
            <a href="{{ route('rdv.calendar') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('rdv.calendar') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="calendar" class="w-4 h-4"></i> Calendrier
            </a>
        </li>
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
            <a href="{{ route('expenses.index', ['facture' => 1]) }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('paiement.*') || request()->routeIs('paiements.*') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="credit-card" class="w-4 h-4"></i> Dépenses / achats
            </a>
        </li>
        <li>
            <a href="{{ route('bons-de-commande.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('bons-de-commande.*') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="package" class="w-4 h-4"></i> Bon de livraison
            </a>
        </li>
        <li>
            <a href="{{ route('emails.notifications') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100 text-gray-700">
                <i data-lucide="bell" class="w-4 h-4"></i> Mes Notifications
            </a>
        </li>
    </ul>

    <!-- Bouton Voir plus -->
    <button id="toggleExtraMenu"
            class="flex items-center gap-2 px-3 py-2 w-full text-left hover:bg-orange-100 text-orange-600 font-medium">
        <i data-lucide="chevron-down" class="w-4 h-4"></i> Voir plus
    </button>

    <!-- Menu caché -->
    <div id="extraMenu" class="hidden">
        <ul class="space-y-1 px-2 py-2 text-gray-500">
            <li><a href="{{ route('fournisseurs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100">
                <i data-lucide="truck" class="w-4 h-4"></i> Fournisseurs</a></li>
            <li><a href="{{ route('produits.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i> Produits</a></li>
            <li><a href="{{ route('poseurs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100">
                <i data-lucide="hammer" class="w-4 h-4"></i> Techniciens</a></li>
            <li><a href="{{ route('stocks.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100">
                <i data-lucide="layers" class="w-4 h-4"></i> Stocks</a></li>
            <li><a href="{{ route('emails.inbox') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100">
                <i data-lucide="message-square" class="w-4 h-4"></i> Messages</a></li>
            <li><a href="{{ route('units.form') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i> Acheter des crédits </li>
            <li><a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100">
                <i data-lucide="users" class="w-4 h-4"></i> Mes utilisateurs</a></li>
            <li><a href="{{ route('consommation.index') }}" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100">
                <i data-lucide="bar-chart-2" class="w-4 h-4"></i> Ma consommation</a></li>
        </ul>
    </div>

    <ul class="space-y-1 px-2 py-2 border-t border-gray-200">
       
        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100"><i data-lucide="log-out" class="w-4 h-4"></i> Déconnexion</a></li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </ul>
</nav>

<!-- Script de gestion de l'état persistant -->
<script>
    const extraMenu = document.getElementById('extraMenu');
    const toggleBtn = document.getElementById('toggleExtraMenu');

    // Appliquer l’état sauvegardé
    if (localStorage.getItem('extraMenuVisible') === 'true') {
        extraMenu.classList.remove('hidden');
    }

    // Toggle + mémorisation
    toggleBtn.addEventListener('click', function () {
        extraMenu.classList.toggle('hidden');
        localStorage.setItem('extraMenuVisible', !extraMenu.classList.contains('hidden'));
    });
</script>