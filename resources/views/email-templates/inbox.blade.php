@extends('layout')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r p-6 flex flex-col justify-between">
        <div>
            <a href="#" class="bg-orange-500 text-white text-sm font-medium px-4 py-2 rounded block text-center hover:bg-orange-600 mb-6">
                + Ajouter un model
            </a>

            <nav class="space-y-3">
                <div class="flex justify-between text-sm font-medium text-gray-700">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-inbox text-orange-500"></i> Inbox
                    </span>
                    <span class="text-gray-400">1253</span>
                </div>
                <div class="flex justify-between text-sm font-medium text-gray-700">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-paper-plane text-gray-500"></i> Sent
                    </span>
                    <span class="text-gray-400">24,532</span>
                </div>
                <div class="flex justify-between text-sm font-medium text-gray-700">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-star text-yellow-400"></i> Important
                    </span>
                    <span class="text-gray-400">18</span>
                </div>
                <div class="flex justify-between text-sm font-medium text-gray-700">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-trash text-gray-400"></i> Bin
                    </span>
                    <span class="text-gray-400">9</span>
                </div>
            </nav>
        </div>

        <div class="space-y-4 text-sm text-gray-600">
            <div class="font-semibold">Autres</div>
            <ul class="space-y-1">
                <li>Recouvrements</li>
                <li>Sidexa</li>
                <li>Stocks</li>
                <li>Poseurs</li>
                <li>Fournisseurs</li>
                <li>Produits</li>
                <li>Messages</li>
                <li>Acheter des unités</li>
                <li>Mes utilisateurs</li>
                <li>Ma consommation</li>
            </ul>
            <hr>
            <div class="text-gray-400 mt-3">⚙️ Settings</div>
            <div class="text-red-500">⏻ Logout</div>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 p-6">
        <!-- Search bar -->
        <div class="mb-6">
            <input type="text" placeholder="Rechercher..." class="w-full border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring focus:border-blue-300">
        </div>

        <!-- Emails list -->
        <div class="bg-white shadow rounded-md">
            @foreach ($templates as $template)
                <div class="flex items-center px-4 py-3 border-b hover:bg-gray-50">
                    <input type="checkbox" class="mr-4">
                    <div class="w-1/4 font-medium">{{ $template->name }}</div>
                    <div class="w-1/6">
                        @php
                            $labels = ['Primary' => 'bg-green-100 text-green-700', 'Work' => 'bg-orange-100 text-orange-700', 'Friends' => 'bg-pink-100 text-pink-700', 'Social' => 'bg-blue-100 text-blue-700'];
                            $badge = $labels[array_rand($labels)];
                            $labelText = array_search($badge, $labels);
                        @endphp
                        <span class="text-xs font-medium px-2 py-1 rounded-full {{ $badge }}">
                            {{ $labelText }}
                        </span>
                    </div>
                    <div class="flex-1 text-sm text-gray-700">
                        {{ Str::limit(strip_tags($template->subject), 70) }}
                    </div>
                </div>
            @endforeach

            @if($templates->isEmpty())
                <div class="text-center text-gray-400 py-8">Aucun modèle d'email trouvé.</div>
            @endif
        </div>
    </div>
</div>
@endsection