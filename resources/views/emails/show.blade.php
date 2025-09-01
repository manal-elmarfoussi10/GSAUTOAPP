@extends('layout')

@section('content')
<div class="flex h-screen bg-gray-50">
    @include('emails.partials.sidebar')

    <div class="flex-1 overflow-y-auto">
        <div class="bg-white shadow-sm p-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="{{ route('emails.inbox') }}" class="mr-3 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-800">{{ $email->subject }}</h1>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto py-6 px-4">
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold text-lg mr-3">
                                {{ substr(optional($email->senderUser)->name ?? '', 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ optional($email->senderUser)->name ?? 'N/A' }}</h3>
                                <div class="text-sm text-gray-500">
                                    <span>à {{ optional($email->receiverUser)->name ?? 'N/A' }}</span>
                                    <span class="mx-1">•</span>
                                    <span>{{ \Carbon\Carbon::parse($email->created_at)->format('d M, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <div class="prose max-w-none text-gray-800 mb-4">
                        {!! $email->content !!}
                    </div>

                    @if($email->file_path && Storage::disk('public')->exists($email->file_path))
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Pièces jointes</h4>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('attachment', ['path' => $email->file_path]) }}" target="_blank" class="flex items-center px-3 py-2 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                                <div class="mr-3 text-orange-500">
                                    <i class="fas fa-file-pdf text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-sm text-gray-800">{{ $email->file_name ?? basename($email->file_path) }}</div>
                                    <div class="text-xs text-gray-500">
                                        <a href="{{ route('attachment', ['path' => $email->file_path]) }}" target="_blank" class="text-blue-500 underline">Download attachment</a>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-between">
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-gray-600 hover:bg-gray-200 rounded">
                            <i class="fas fa-reply mr-1"></i> Répondre
                        </button>
                        <button class="px-3 py-1 text-gray-600 hover:bg-gray-200 rounded">
                            <i class="fas fa-reply-all mr-1"></i> Répondre à tous
                        </button>
                        <button class="px-3 py-1 text-gray-600 hover:bg-gray-200 rounded">
                            <i class="fas fa-share mr-1"></i> Transférer
                        </button>
                    </div>
                </div>
            </div>

            @if($email->replies && $email->replies->count())
            <div class="space-y-4">
                @foreach($email->replies as $reply)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm mr-2">
                                    {{ substr(optional($reply->senderUser)->name ?? '', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ optional($reply->senderUser)->name ?? 'N/A' }}</h3>
                                    <div class="text-xs text-gray-500">
                                        <span>{{ \Carbon\Carbon::parse($reply->created_at)->format('d M, H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4">
                        <div class="prose max-w-none text-gray-800">
                            {!! $reply->content !!}
                        </div>

                        @if ($reply->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($reply->file_path))
                        <div class="flex items-center space-x-2 mt-2">
                            <a href="{{ route('attachment', ['path' => $reply->file_path]) }}" target="_blank" class="flex items-center space-x-2 text-blue-600 hover:underline">
                                <i class="fas fa-file text-sm"></i>
                                <span class="text-xs font-medium">{{ $reply->file_name }}</span>
                                <span class="text-gray-400 text-xs">
                                    {{ number_format(\Illuminate\Support\Facades\Storage::disk('public')->size($reply->file_path) / 1024, 2) }} KB
                                </span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Reply Form --}}
            <div class="bg-white shadow-lg rounded-lg border border-gray-200 mt-8">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-base font-medium text-gray-800">Répondre à l'email</h3>
                </div>

                @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mx-4 mt-4 rounded">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Veuillez corriger les erreurs suivantes :</h3>
                            <ul class="list-disc pl-5 text-sm text-red-700 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <form action="{{ route('emails.reply', $email->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 p-4">
                    @csrf
                    <textarea id="content" name="content" rows="5" class="w-full border rounded p-4" placeholder="Écrivez votre réponse ici...">{{ old('content') }}</textarea>
                    <div class="flex justify-between items-center">
                        <label for="file" class="cursor-pointer text-gray-500 hover:text-orange-500">
                            <i class="fas fa-paperclip"></i>
                            <input type="file" name="file" id="file" class="hidden" onchange="showAttachmentPreview(this)">
                        </label>
                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2 rounded-lg font-medium flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Envoyer
                        </button>
                    </div>

                    <div id="attachment-preview" class="hidden bg-gray-50 p-3 rounded-lg border border-gray-200 mt-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-paperclip text-gray-500 mr-2"></i>
                                <span id="file-name" class="text-sm font-medium truncate max-w-xs"></span>
                            </div>
                            <button type="button" onclick="removeAttachment()" class="text-gray-500 hover:text-red-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.25.1/full/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('content')) {
            CKEDITOR.replace('content', {
                toolbar: [
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
                    { name: 'links', items: ['Link', 'Unlink'] },
                    { name: 'insert', items: ['Image', 'Table'] },
                    { name: 'tools', items: ['Maximize'] }
                ],
                height: 150,
                versionCheck: false,
                filebrowserUploadUrl: "{{ route('emails.upload') }}",
                filebrowserUploadMethod: 'form'
            });
        }
    });

    function showAttachmentPreview(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            document.getElementById('file-name').textContent = fileName;
            document.getElementById('attachment-preview').classList.remove('hidden');
        }
    }

    function removeAttachment() {
        document.getElementById('file').value = '';
        document.getElementById('attachment-preview').classList.add('hidden');
    }
</script>
@endsection