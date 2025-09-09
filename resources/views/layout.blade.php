<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>GS AUTO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome & Tailwind -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

    <style>
        .ring-orange-custom {
            --tw-ring-color: #FF4B00;
        }
    </style>
</head>
<body class="flex min-h-screen bg-gray-100 font-sans text-sm text-gray-800">

<!-- Sidebar -->
<aside class="w-64 bg-white shadow-md flex flex-col">
    @php $role = auth()->user()->role ?? ''; @endphp
    @includeIf('layouts.sidebars.' . $role)
</aside>

<!-- Main Content -->
<div class="flex-1 flex flex-col">

   <nav class="bg-white px-4 py-3 flex justify-between items-center shadow text-sm">

    <!-- Barre de recherche  -->
    <div class="relative w-20">
   
    </div>

<!-- Navigation + Infos utilisateur -->
<div class="flex items-center gap-1.5 font-medium">

    @php
        $user = auth()->user();
        $role = $user->role ?? '';

        // Base items
        $navItems = [
            ['label' => 'FONCTIONNALITÉS', 'href' => url('fonctionnalites')],
            ['label' => 'CONTACT',         'href' => url('contact')],
        ];

        // Role-specific dashboard link
        if ($role === 'poseur') {
            $navItems[] = ['label' => 'MON COMPTE', 'href' => url('mon-compte')];
            $navItems[] = ['label' => 'DASHBOARD',  'href' => url('dashboard/poseur')];
        } elseif ($role === 'superadmin') {
            $navItems[] = ['label' => 'MON COMPTE', 'href' => url('mon-compte')];
            $navItems[] = ['label' => 'DASHBOARD',  'href' => route('superadmin.dashboard')];
        } else {
            $navItems[] = ['label' => 'MON COMPTE', 'href' => url('mon-compte')];
            $navItems[] = ['label' => 'DASHBOARD',  'href' => url('dashboard')];
        }
    @endphp

    @foreach ($navItems as $item)
        @php
            // active state by current path
            $isActive = request()->fullUrlIs($item['href'].'*') || request()->is(trim(parse_url($item['href'], PHP_URL_PATH), '/').'*');
        @endphp
        <a href="{{ $item['href'] }}"
           class="px-2 py-1 rounded transition duration-150 focus:outline-none focus:ring-2 focus:ring-[#FF4B00]
                  {{ $isActive ? 'bg-[#FF4B00] text-white' : 'text-[#FF4B00] hover:bg-[#FFA366] hover:text-white' }}">
            {{ $item['label'] }}
        </a>
    @endforeach

    {{-- Unités (hide for superadmin) --}}
    @if ($role !== 'superadmin')
        @php $isUnit = request()->is('acheter-unites'); @endphp
        <a href="{{ url('/acheter-unites') }}"
           class="px-2 py-1 rounded transition duration-150 focus:outline-none focus:ring-2 focus:ring-[#FF4B00]
                  {{ $isUnit ? 'bg-[#FF4B00] text-white' : 'text-[#FF4B00] hover:bg-[#FFA366] hover:text-white' }}">
                  Crédit : <span class="font-bold">{{ auth()->user()->company?->units ?? 0 }}</span>
        </a>
    @endif

    <!-- Notifications -->
    <button class="ml-1 focus:outline-none focus:ring-2 focus:ring-[#FF4B00] rounded-full">
        <i data-lucide="bell" class="w-4 h-4 text-[#FF4B00]"></i>
    </button>

    <!-- Avatar + nom -->
    <a href="{{ route('mon-compte') }}" class="flex items-center gap-1 ml-2 hover:opacity-80 transition max-w-[140px]">
        @if ($user && $user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo))
            <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo" class="h-7 w-7 rounded-full object-cover border-2 border-[#FF4B00] shadow" />
        @else
            <div class="h-7 w-7 bg-[#FF4B00] text-white rounded-full flex items-center justify-center font-bold text-xs uppercase">
                {{ strtoupper($user->name[0] ?? 'U') }}
            </div>
        @endif
        <span class="text-[#FF4B00] truncate text-sm">{{ $user->name ?? 'Utilisateur' }}</span>
    </a>
</div>
</nav>


    <!-- Main -->
    <main class="p-6">
        @yield('content')
    </main>
</div>

<script>
    lucide.createIcons();
</script>

@yield('scripts')
</body>
</html>
