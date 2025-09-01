@extends('layout')

@section('content')
<div class="flex h-screen bg-gray-50">
    {{-- Sidebar --}}
    @include('emails.partials.sidebar')

    {{-- Main Content --}}
    <div class="flex-1 overflow-y-auto p-4">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
            {{-- Form Header --}}
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-white">Compose New Message</h2>
                    <button type="button" onclick="window.history.back()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mx-6 mt-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were {{ $errors->count() }} errors with your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form Content --}}
            <form method="POST" action="{{ route('emails.store') }}" enctype="multipart/form-data" class="space-y-6 px-6 py-4">
                @csrf
                <input type="hidden" name="sender" value="{{ auth()->user()->name }}" />
                {{-- Recipient --}}
                <div class="relative">
                    <label for="receiver_id" class="block text-sm font-medium text-gray-700 mb-1">To:</label>
                    <div class="mt-1 relative">
                        <select name="receiver_id" id="receiver_id" required
                                class="block w-full pl-3 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm">
                            <option value="">Select recipient</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

             

                {{-- Subject --}}
                <div class="relative">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject:</label>
                    <div class="mt-1 relative">
                        <input type="text" name="subject" id="subject"
                               class="block w-full pl-3 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                               placeholder="Email subject" value="{{ old('subject') }}">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-tag text-gray-400"></i>
                        </div>
                    </div>
                </div>

                {{-- Content (CKEditor) --}}
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Message:</label>
                    <div class="mt-1 border border-gray-300 rounded-lg overflow-hidden">
                        <textarea name="content" id="content"
                                  class="block w-full border-0 focus:ring-0 sm:text-sm"
                                  rows="8" placeholder="Write your message here...">{{ old('content') }}</textarea>
                    </div>
                </div>

                {{-- Attachment Preview Area --}}
                <div id="attachment-preview" class="hidden bg-gray-50 p-3 rounded-lg border border-dashed border-gray-300">
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

                {{-- File Upload --}}
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Attachments:</label>
                    <div class="mt-1">
                        <div class="flex items-center justify-center w-full">
                            <label for="file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500">
                                        <span class="font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                                </div>
                                <input id="file" name="file" type="file" class="hidden" onchange="showAttachmentPreview(this)" />
                            </label>
                        </div> 
                    </div>
                </div>

                {{-- Filename (Optional) --}}
                <div id="filename-container" class="hidden">
                    <label for="filename" class="block text-sm font-medium text-gray-700 mb-1">Attachment Name:</label>
                    <div class="mt-1">
                        <input type="text" name="filename" id="filename"
                               class="block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                               placeholder="Custom name for attachment">
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex space-x-2">
                        <label for="file" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            <i class="fas fa-paperclip mr-2"></i> Add Attachment
                        </label>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="window.history.back()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Discard
                        </button>
                        <button type="submit" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            <i class="fas fa-paper-plane mr-2"></i> Send
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- CKEditor Integration --}}
    <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById('content')) {
                CKEDITOR.replace('content', {
                    toolbar: [
                        { name: 'document', items: ['Source', '-', 'Preview'] },
                        { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                        { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                        { name: 'links', items: ['Link', 'Unlink'] },
                        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar'] },
                        { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                        { name: 'colors', items: ['TextColor', 'BGColor'] },
                        { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
                    ],
                    height: 300,
                    versionCheck: false,
                    filebrowserUploadUrl: "{{ route('emails.upload') }}", // Add this route
                    filebrowserUploadMethod: 'form'
                });
            }
        });

        // Attachment preview functionality
        function showAttachmentPreview(input) {
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                document.getElementById('file-name').textContent = fileName;
                document.getElementById('attachment-preview').classList.remove('hidden');
                document.getElementById('filename-container').classList.remove('hidden');
                
                // Set default filename if not provided
                if (!document.getElementById('filename').value) {
                    const nameWithoutExt = fileName.split('.').slice(0, -1).join('.');
                    document.getElementById('filename').value = nameWithoutExt;
                }
            }
        }

        function removeAttachment() {
            document.getElementById('file').value = '';
            document.getElementById('attachment-preview').classList.add('hidden');
            document.getElementById('filename-container').classList.add('hidden');
            document.getElementById('filename').value = '';
        }
    </script>
@endsection