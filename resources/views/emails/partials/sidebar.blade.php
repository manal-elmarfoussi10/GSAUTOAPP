<div class="w-64 bg-white border-r p-4">
    <!-- Add Model Button -->
    <a href="{{ route('emails.create') ?? url('/emails/create') }}"
       class="bg-orange-500 hover:bg-orange-600 text-white font-semibold w-full py-2 rounded mb-4 text-center block">
        + Ajouter un modèle
    </a>

    <ul class="space-y-1">
        <!-- Inbox -->
        <li>
            <a href="{{ route('emails.inbox') ?? url('/emails') }}"
               class="flex items-center justify-between px-3 py-2 rounded
               {{ request()->routeIs('emails.inbox') ? 'bg-orange-100 text-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                <span class="flex items-center gap-2">
                    @svg('heroicon-o-inbox', 'w-4 h-4')
                    Inbox
                </span>
                
            </a>
        </li>

        <!-- Sent -->
        <li>
            <a href="{{ route('emails.sent') ?? url('/emails/sent') }}"
               class="flex items-center justify-between px-3 py-2 rounded
               {{ request()->routeIs('emails.sent') ? 'bg-orange-100 text-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                <span class="flex items-center gap-2">
                    @svg('heroicon-o-paper-airplane', 'w-4 h-4')
                    Sent
                </span>
                
            </a>
        </li>

        <!-- Important -->
        <li>
            <a href="{{ route('emails.important') ?? url('/emails/important') }}"
               class="flex items-center justify-between px-3 py-2 rounded
               {{ request()->routeIs('emails.important') ? 'bg-orange-100 text-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                <span class="flex items-center gap-2">
                    @svg('heroicon-o-star', 'w-4 h-4')
                    Important
                </span>
                
            </a>
        </li>

        <!-- Bin -->
        <li>
            <a href="{{ route('emails.bin') ?? url('/emails/bin') }}"
               class="flex items-center justify-between px-3 py-2 rounded
               {{ request()->routeIs('emails.bin') ? 'bg-orange-100 text-orange-500' : 'text-gray-700 hover:bg-gray-100' }}">
                <span class="flex items-center gap-2">
                    @svg('heroicon-o-trash', 'w-4 h-4')
                    Bin
                </span>
                
            </a>
        </li>
    </ul>
</div>