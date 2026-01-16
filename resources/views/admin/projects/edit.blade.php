<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($errors->any())
                        <ul class="text-sm text-red-600 space-y-1 mb-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ route('admin.projects.update', $project->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title: </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="title" type="text" id="title" value="{{ old('title', $project->title) }}" required>
                        </div>

                        <div class="mb-4">
                             <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description: </label>
                             <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="description" id="description">{{ old('description', $project->description) }}</textarea>
                        </div>

                        <div class="mb-4" x-data="imagePreview()">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Project Image</label>
                            
                            <!-- Image Preview Preview -->
                            <div class="mb-3 bg-gray-200 h-64 rounded-lg flex items-center justify-center overflow-hidden border">
                                <template x-if="imageUrl">
                                    <img :src="imageUrl" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!imageUrl">
                                    <span class="text-gray-400">Image Preview</span>
                                </template>
                            </div>

                            <div class="mb-2">
                                <div class="flex items-center mb-2">
                                    <input type="radio" id="upload_file" x-model="uploadType" value="file" class="mr-2">
                                    <label for="upload_file" class="text-xs font-bold uppercase text-gray-600 cursor-pointer">Upload File</label>
                                    
                                    <input type="radio" id="upload_url" x-model="uploadType" value="url" class="ml-4 mr-2">
                                    <label for="upload_url" class="text-xs font-bold uppercase text-gray-600 cursor-pointer">From URL</label>
                                </div>
                            </div>
                            
                            <!-- File Upload -->
                            <div x-show="uploadType === 'file'">
                                <input type="file" name="image_file" @change="fileChosen" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                            </div>
                            
                            <!-- URL Input -->
                            <div x-show="uploadType === 'url'">
                                <input type="url" name="image_url" x-model="urlInput" @input="urlChanged" placeholder="https://example.com/image.jpg" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                            </div>
                        </div>

                        @push('scripts')
                        <script>
                            function imagePreview() {
                                return {
                                    uploadType: 'file',
                                    imageUrl: '{{ Str::startsWith($project->image, "http") ? $project->image : ($project->image ? asset($project->image) : "") }}',
                                    urlInput: '{{ filter_var($project->image, FILTER_VALIDATE_URL) ? $project->image : "" }}',
                                    
                                    init() {
                                         if (this.urlInput) {
                                             this.uploadType = 'url';
                                         }
                                    },
                                    
                                    fileChosen(event) {
                                        this.fileToDataUrl(event, src => this.imageUrl = src)
                                    },
                                    
                                    urlChanged() {
                                        this.imageUrl = this.urlInput;
                                    },
                    
                                    fileToDataUrl(event, callback) {
                                        if (! event.target.files.length) return
                                        let file = event.target.files[0],
                                            reader = new FileReader()
                                        reader.readAsDataURL(file)
                                        reader.onload = e => callback(e.target.result)
                                    }
                                }
                            }
                        </script>
                        @endpush

                        <div class="mb-4">
                            <label for="tags" class="block text-gray-700 text-sm font-bold mb-2">Tags (comma-separated): </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="tags" type="text" id="tags" value="{{ old('tags', is_array($project->tags) ? implode(', ', $project->tags) : $project->tags) }}" placeholder="Laravel, Vue, React">
                        </div>

                        <div class="mb-4">
                            <label for="live_url" class="block text-gray-700 text-sm font-bold mb-2">Live URL: </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="live_url" type="url" id="live_url" value="{{ old('live_url', $project->live_url) }}" placeholder="https://example.com">
                        </div>

                        <div class="mb-4">
                            <label for="github_url" class="block text-gray-700 text-sm font-bold mb-2">GitHub URL: </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="github_url" type="url" id="github_url" value="{{ old('github_url', $project->github_url) }}" placeholder="https://github.com/username/repo">
                        </div>

                        <div class="mb-4">
                            <label for="list_order" class="block text-gray-700 text-sm font-bold mb-2">List Order: </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="list_order" type="number" id="list_order" value="{{ old('list_order', $project->list_order ?? 0) }}" min="0">
                        </div>

                        <div class="mb-4 flex items-center gap-6">
                            <div class="flex items-center">
                                <input type="checkbox" name="status" id="status" value="1" {{ old('status', $project->status) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="status" class="ml-2 text-gray-700 text-sm font-bold">Active</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="featured_homepage" id="featured_homepage" value="1" {{ old('featured_homepage', $project->featured_homepage) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="featured_homepage" class="ml-2 text-gray-700 text-sm font-bold">Featured on Homepage</label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Update
                            </button>
                            <a href="{{ route('admin.projects.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
