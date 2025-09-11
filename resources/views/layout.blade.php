<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>GG AUTO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Favicons & manifest --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" type="image/png" sizes="96x96"  href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('web-app-manifest-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('web-app-manifest-512x512.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#FF4B00">

    {{-- UI libs --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="flex min-h-screen bg-gray-100 font-sans text-sm text-gray-800">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow-md flex flex-col">
        @php $role = auth()->user()->role ?? ''; @endphp
        @includeIf('layouts.sidebars.' . $role)
    </aside>

    {{-- Main wrapper --}}
    <div class="flex-1 flex flex-col">

        {{-- HEADER / NAV COMPACT --}}
        <nav class="bg-white px-4 py-2 shadow text-sm">
          {{-- petit espace global entre éléments --}}
          <div class="mx-auto flex items-center justify-between gap-4">

            {{-- Barre de recherche encore plus petite + marge à droite --}}
            <form action="{{ route('search') }}" method="GET" class="relative flex-1 max-w-[320px] mr-2 md:mr-4">
              <label for="globalSearch" class="sr-only">Rechercher</label>
              <input
                id="globalSearch"
                type="search"
                name="q"
                value="{{ request('q') }}"
                autocomplete="off"
                placeholder="Rechercher… (clients, devis, factures)"
                class="w-full h-7 pl-6 pr-7 rounded-md border border-gray-300/80
                       text-[12px] placeholder:text-gray-400 outline-none
                       focus:ring-1 focus:ring-[#FF4B00] focus:border-[#FF4B00]"
              />
              <i class="fa-solid fa-magnifying-glass absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>

              {{-- Suggestions compactes --}}
              <div id="searchSuggest"
                   class="hidden absolute z-40 mt-1 w-full rounded-md border border-gray-200 bg-white shadow-lg overflow-hidden">
                <ul id="searchSuggestList" class="max-h-56 overflow-y-auto divide-y divide-gray-100"></ul>
                <div class="px-3 py-1 text-[10.5px] text-gray-400">↵ Entrer pour tous les résultats</div>
              </div>
            </form>

            {{-- Liens/Actions (desktop) --}}
            <div class="hidden md:flex items-center gap-1.5 font-medium">
              @php
                $user = auth()->user();
                $role = $user->role ?? '';
                $navItems = [
                  ['label' => 'FONCTIONNALITÉS', 'href' => url('fonctionnalites')],
                  ['label' => 'CONTACT',         'href' => url('contact')],
                  ['label' => 'MON COMPTE',      'href' => url('mon-compte')],
                  ['label' => 'DASHBOARD',       'href' => $role==='poseur'
                                                  ? url('dashboard/poseur')
                                                  : ($role==='superadmin' ? route('superadmin.dashboard') : url('dashboard'))],
                ];
              @endphp

              @foreach ($navItems as $item)
                @php
                  $isActive = request()->fullUrlIs($item['href'].'*')
                    || request()->is(trim(parse_url($item['href'], PHP_URL_PATH), '/').'*');
                @endphp
                <a href="{{ $item['href'] }}"
                   class="px-2 py-1 rounded-md transition focus:outline-none focus:ring-1 focus:ring-[#FF4B00]
                          {{ $isActive ? 'bg-[#FF4B00] text-white' : 'text-[#FF4B00] hover:bg-[#FFA366] hover:text-white' }}">
                  {{ $item['label'] }}
                </a>
              @endforeach

              @if ($role !== 'superadmin')
                <a href="{{ url('/acheter-unites') }}"
                   class="ml-1.5 px-2 py-1 rounded-md text-[#FF4B00] hover:bg-[#FFA366] hover:text-white transition
                          focus:outline-none focus:ring-1 focus:ring-[#FF4B00]">
                  NB UNITÉS : <span class="font-bold">{{ $user->company?->units ?? 0 }}</span>
                </a>
              @endif

              <button class="ml-1 rounded-full p-1 focus:outline-none focus:ring-1 focus:ring-[#FF4B00]">
                <i data-lucide="bell" class="w-4 h-4 text-[#FF4B00]"></i>
              </button>

              <a href="{{ route('mon-compte') }}" class="flex items-center gap-1 ml-2 hover:opacity-80 transition max-w-[140px]">
                @if ($user && $user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo))
                  <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo" class="h-7 w-7 rounded-full object-cover border-2 border-[#FF4B00] shadow" />
                @else
                  <div class="h-7 w-7 bg-[#FF4B00] text-white rounded-full flex items-center justify-center font-bold text-xs">
                    {{ strtoupper($user->name[0] ?? 'U') }}
                  </div>
                @endif
                <span class="text-[#FF4B00] truncate text-sm">{{ $user->name ?? 'Utilisateur' }}</span>
              </a>
            </div>

            {{-- Raccourcis mobile --}}
            <div class="md:hidden flex items-center gap-2">
              <a href="{{ url('dashboard') }}" class="p-1 rounded-md text-[#FF4B00] hover:bg-[#FFA366] hover:text-white">
                <i class="fa-solid fa-gauge-high text-sm"></i>
              </a>
              <a href="{{ url('mon-compte') }}" class="p-1 rounded-md text-[#FF4B00] hover:bg-[#FFA366] hover:text-white">
                <i class="fa-solid fa-user text-sm"></i>
              </a>
            </div>

          </div>
        </nav>

        {{-- Contenu --}}
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    <script>lucide.createIcons();</script>

    {{-- JS des suggestions (inchangé) --}}
    <script>
    (function(){
      const input = document.getElementById('globalSearch');
      if(!input) return;

      const box  = document.getElementById('searchSuggest');
      const list = document.getElementById('searchSuggestList');

      let timer=null, cursor=-1;
      const debounce = (fn, d=250) => (...a)=>{ clearTimeout(timer); timer=setTimeout(()=>fn(...a), d); };

      function hideBox(){ box.classList.add('hidden'); cursor=-1; }
      function showBox(){ box.classList.remove('hidden'); }
      function render(items){
        list.innerHTML = items.map((it)=>`
          <li>
            <a href="${it.url}" class="flex items-center gap-3 px-3 py-2 hover:bg-orange-50">
               <i class="fa-solid ${it.icon} text-[#FF4B00]"></i>
               <span class="text-sm"><span class="font-medium capitalize">${it.type}</span> — ${it.label}</span>
            </a>
          </li>`).join('') || `<li class="px-3 py-2 text-sm text-gray-500">Aucun résultat…</li>`;
      }

      const fetchSuggest = debounce(async (q)=>{
        if(!q || q.length<2){ hideBox(); return; }
        try{
          const res = await fetch(`{{ route('search.suggest') }}?q=${encodeURIComponent(q)}`, {
            headers: {'X-Requested-With':'XMLHttpRequest'}
          });
          const data = await res.json();
          render(data); showBox();
        }catch(e){ hideBox(); }
      }, 250);

      input.addEventListener('input', e=> fetchSuggest(e.target.value));
      input.addEventListener('focus', e=> fetchSuggest(e.target.value));
      document.addEventListener('click', (e)=> {
        if(!e.target.closest('#globalSearch') && !e.target.closest('#searchSuggest')) hideBox();
      });

      input.addEventListener('keydown', (e)=>{
        const items = [...list.querySelectorAll('a')];
        if (!items.length) return;

        if (e.key === 'ArrowDown'){ e.preventDefault(); cursor = (cursor+1) % items.length; }
        else if (e.key === 'ArrowUp'){ e.preventDefault(); cursor = (cursor-1+items.length) % items.length; }
        else if (e.key === 'Enter' && cursor >= 0){ e.preventDefault(); items[cursor].click(); return; }
        else { return; }

        items.forEach(a=>a.parentElement.classList.remove('bg-orange-50'));
        items[cursor].parentElement.classList.add('bg-orange-50');
        items[cursor].scrollIntoView({block:'nearest'});
      });
    })();
    </script>

    @yield('scripts')
</body>
</html>

