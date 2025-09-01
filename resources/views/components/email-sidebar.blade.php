<div class="w-64 p-4 border-r bg-white">
    <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded font-semibold mb-6">
        + Ajouter un model
    </button>

    <h3 class="text-sm font-semibold text-gray-700 mb-3">Mes emails</h3>
    <ul class="space-y-2 text-sm text-gray-700">
        <li>
            <a href="{{ route('emails.inbox') }}"
               class="flex items-center px-3 py-2 rounded hover:bg-orange-100 transition {{ request()->routeIs('emails.inbox') ? 'bg-orange-100 font-bold text-orange-600' : '' }}">
                <x-heroicon-o-inbox class="w-5 h-5 mr-2" />
                Inbox
                <span class="ml-auto text-xs text-gray-500 font-medium">1253</span>
            </a>
        </li>
        <li>
            <a href="{{ route('emails.sent') }}"
               class="flex items-center px-3 py-2 rounded hover:bg-gray-100 transition {{ request()->routeIs('emails.sent') ? 'font-bold text-orange-600' : '' }}">
                <x-heroicon-o-paper-airplane class="w-5 h-5 mr-2" />
                Sent
                <span class="ml-auto text-xs text-gray-500">24,532</span>
            </a>
        </li>
        <li>
            <a href="{{ route('emails.important') }}"
               class="flex items-center px-3 py-2 rounded hover:bg-gray-100 transition {{ request()->routeIs('emails.important') ? 'font-bold text-orange-600' : '' }}">
                <x-heroicon-o-star class="w-5 h-5 mr-2" />
                Important
                <span class="ml-auto text-xs text-gray-500">18</span>
            </a>
        </li>
        <li>
            <a href="{{ route('emails.bin') }}"
               class="flex items-center px-3 py-2 rounded hover:bg-gray-100 transition {{ request()->routeIs('emails.bin') ? 'font-bold text-orange-600' : '' }}">
                <x-heroicon-o-trash class="w-5 h-5 mr-2" />
                Bin
                <span class="ml-auto text-xs text-gray-500">9</span>
            </a>
        </li>
    </ul>
</div>