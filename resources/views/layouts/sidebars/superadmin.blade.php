<div class="p-3 border-b border-gray-200 flex justify-left">
    <img src="{{ asset('images/GS.png') }}" alt="GG AUTO Logo" class="h-20" />
</div>

<nav class="flex-1 overflow-y-auto text-sm text-gray-700">
    <ul class="space-y-1 px-2 py-4">
        <li>
            <a href="{{ route('superadmin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('superadmin.dashboard') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="{{ route('superadmin.companies.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('superadmin.companies.*') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="building-2" class="w-4 h-4"></i> Sociétés
            </a>
        </li>

        <li>
            <a href="{{ route('superadmin.companies.create') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('superadmin.companies.create') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
                <i data-lucide="plus-square" class="w-4 h-4"></i> Nouvelle société
            </a>
        </li>

        <li>
            <a href="{{ route('superadmin.global-users.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded {{ request()->routeIs('superadmin.global-users.*') ? 'bg-[#FF4B00] text-white font-semibold' : 'hover:bg-orange-100 text-gray-700' }}">
              <i data-lucide="users" class="w-4 h-4"></i> Utilisateurs globaux
            </a>
          </li>
    </ul>

    <ul class="space-y-1 px-2 py-2 border-t border-gray-200">
        <li>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-orange-100">
                <i data-lucide="log-out" class="w-4 h-4"></i> Déconnexion
            </a>
        </li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </ul>
</nav>