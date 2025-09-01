@foreach($emails as $email)
  <div class="p-4 border rounded-md {{ $email->folder === 'sent' ? 'bg-blue-50' : 'bg-gray-50' }}">
    <p class="font-medium">
      {{ optional($email->senderUser)->name ?? $email->sender }}
      <span class="text-xs text-gray-500">{{ $email->created_at->format('d/m/Y H:i') }}</span>
    </p>
    <p class="mb-2">{{ $email->content }}</p>
    @if($email->file_path)
      <a href="{{ asset('storage/'.$email->file_path) }}" target="_blank"
         class="text-blue-600 underline text-sm">
         Download
      </a>
    @endif
    @foreach($email->replies as $reply)
      <div class="mt-4 ml-6 p-3 border rounded-md bg-white">
        <div class="flex justify-between mb-1">
          <strong>{{ optional($reply->senderUser)->name ?? $reply->sender_id }}</strong>
          <span class="text-xs text-gray-500">{{ $reply->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div>{{ $reply->content }}</div>
        @if($reply->file_path)
        <a href="{{ route('conversations.download', $reply) }}" target="_blank"
            class="text-blue-600 underline text-sm">
            Download
        </a>
        @endif
      </div>
    @endforeach
  </div>
@endforeach