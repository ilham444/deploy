<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Add New Module') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Fill in the module details to publish it to the platform.</p>
            </div>
        </div>
    </x-slot>

    {{-- Using Alpine.js v3 is highly recommended, but this code remains compatible --}}
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.modul.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- Main Content Column --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Module Title Card --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Module Title</label>
                            <input type="text" name="title" id="title"
                                class="mt-1 block w-full text-lg font-semibold p-3 rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                value="{{ old('title') }}" placeholder="Example: Introduction to Laravel 11" required>
                            @error('title') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>

                        {{-- Module Description Card --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Description</label>
                            {{-- NOTE: For the best experience, integrate a Rich Text editor like TinyMCE or Trix with this textarea. --}}
                            <textarea name="description" id="description" rows="10"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Explain in detail what will be learned in this module..."
                                required>{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Sidebar Settings Column --}}
                    <div class="lg:col-span-1 space-y-6">
                        {{-- Basic Settings Card --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Settings</h3>
                            
                            {{-- Category Input --}}
                            <div>
                                <label for="kategori_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                <select name="kategori_id" id="kategori_id" class="mt-1 block w-full p-3 rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="" disabled selected>-- Select a Category --</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" @selected(old('kategori_id') == $kategori->id)>
                                            {{ $kategori->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>
                            
                            {{-- Estimated Time Input --}}
                            <div class="mt-4">
                                <label for="estimated" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estimated Time (minutes)</label>
                                <input type="number" name="estimated" id="estimated" class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-900 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('estimated') }}" required min="1" placeholder="Example: 45">
                                @error('estimated') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- [REFACTORED] Main Content Upload Card (Image, PDF, Word, Video) --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Main Module Content</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Upload the main file for this module (Image, PDF, Document, or Video).</p>
                            <div x-data="fileUploader('thumbnail')" class="relative flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-center cursor-pointer hover:border-indigo-500 dark:hover:border-indigo-400 transition-colors"
                                @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false" @drop.prevent="dragging = false; handleFileSelect($event)">
                                <div class="absolute inset-0 bg-indigo-50 dark:bg-indigo-500/10" x-show="dragging"></div>
                                <input type="file" name="thumbnail" id="thumbnail" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFileSelect($event)" accept="image/*,video/mp4,video/webm,.pdf,.doc,.docx" required>
                                
                                {{-- Default View --}}
                                <div class="relative z-10" x-show="!fileName">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3 3m3-3l3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300"><span class="font-semibold text-indigo-600 dark:text-indigo-400">Drag a file</span> or click to upload</p>
                                    <p class="mt-1 text-xs text-gray-500">Image, PDF, DOCX, Video (Max. 10MB)</p>
                                </div>

                                {{-- View After File is Selected --}}
                                <div class="relative z-10" x-show="fileName" x-cloak>
                                    <div class="flex items-center gap-3"><svg class="h-10 w-10 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                                        <div><p class="text-sm font-medium text-gray-800 dark:text-gray-200" x-text="fileName"></p><p class="text-xs text-gray-500" x-text="fileSize"></p></div>
                                    </div>
                                    <button type="button" @click="removeFile()" class="mt-3 text-xs text-red-500 hover:underline">Change file</button>
                                </div>
                            </div>
                            @error('thumbnail') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Bottom Action Bar --}}
                <div class="fixed bottom-0 left-0 w-full bg-white dark:bg-gray-800/80 backdrop-blur-sm border-t border-gray-200 dark:border-gray-700 z-30">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-3">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('admin.modul.index') }}" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</a>
                            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded-lg shadow-md transition-colors">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3 3m3-3l3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                                Save & Publish
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Reusable Alpine.js function to handle file uploads.
        function fileUploader(inputId) {
            return {
                inputId: inputId,
                dragging: false,
                fileName: '',
                fileSize: '',
                handleFileSelect(event) {
                    let fileInput = document.getElementById(this.inputId);
                    let file;
                    if (event.type === 'drop' && event.dataTransfer) {
                        file = event.dataTransfer.files[0];
                        fileInput.files = event.dataTransfer.files; 
                    } else if (event.target.files.length > 0) {
                        file = event.target.files[0];
                    }
                    if (file) {
                        this.fileName = file.name;
                        this.fileSize = this.formatBytes(file.size);
                    }
                },
                removeFile() {
                    document.getElementById(this.inputId).value = '';
                    this.fileName = '';
                    this.fileSize = '';
                },
                formatBytes(bytes, decimals = 2) {
                    if (!+bytes) return '0 Bytes';
                    const k = 1024;
                    const dm = decimals < 0 ? 0 : decimals;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
                }
            }
        }
    </script>
    {{-- IMPORTANT NOTE FOR BACKEND:
        Inside ModulController@store, you only need to handle the single `thumbnail` file input.

        1. Update your validation to accept all allowed file types:
           'thumbnail' => 'required|file|mimes:png,jpg,jpeg,webp,pdf,doc,docx,mp4,webm|max:10240', // max 10MB

        2. Continue the file saving process as usual. There's no need to handle an `attachment` input anymore.
    --}}
</x-app-layout>