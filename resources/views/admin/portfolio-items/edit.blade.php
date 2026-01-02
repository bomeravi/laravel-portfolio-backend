<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Portfolio Item') }}
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

                    <form method="POST" action="{{ route('admin.portfolio-items.update', $portfolioItem->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <div>
                                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title: </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="title" type="text" id="title" value="{{ old('title', $portfolioItem->title) }}" required>
                                </div>
                                <div>
                                    <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category: </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="category" type="text" id="category" value="{{ old('category', $portfolioItem->category) }}" required>
                                </div>
                                <div>
                                    <label for="tags" class="block text-gray-700 text-sm font-bold mb-2">Tags (comma separated): </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="tags" type="text" id="tags" value="{{ old('tags', is_array($portfolioItem->tags) ? implode(', ', $portfolioItem->tags) : $portfolioItem->tags) }}">
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                     <div class="flex items-center">
                                        <input type="checkbox" name="status" id="status" class="form-checkbox h-5 w-5 text-blue-600" {{ old('status', $portfolioItem->status) ? 'checked' : '' }}>
                                        <label for="status" class="ml-2 text-gray-700">Active</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="featured_homepage" id="featured_homepage" class="form-checkbox h-5 w-5 text-blue-600" {{ old('featured_homepage', $portfolioItem->featured_homepage) ? 'checked' : '' }}>
                                        <label for="featured_homepage" class="ml-2 text-gray-700">Featured On Homepage</label>
                                    </div>
                                </div>

                                <div>
                                     <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description: </label>
                                     <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="description" id="description" rows="4">{{ old('description', $portfolioItem->description) }}</textarea>
                                </div>
                            </div>

                            <!-- Right Column: Image -->
                            <div x-data="imagePreview()" class="space-y-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Image Source</label>
                                <div class="flex gap-4 mb-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio" name="image_source" value="file" x-model="uploadType">
                                        <span class="ml-2">Upload File</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio" name="image_source" value="url" x-model="uploadType">
                                        <span class="ml-2">From URL</span>
                                    </label>
                                </div>

                                <div x-show="uploadType === 'file'">
                                    <label for="image_file" class="block text-gray-700 text-sm font-bold mb-2">Upload Image</label>
                                    <input type="file" name="image_file" id="image_file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" @change="fileChosen">
                                </div>

                                <div x-show="uploadType === 'url'" style="display: none;">
                                    <label for="image_url" class="block text-gray-700 text-sm font-bold mb-2">Image URL</label>
                                    <input type="url" name="image_url" id="image_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" x-model="urlInput" @input="urlChanged">
                                </div>

                                <div x-show="imageUrl" class="mt-4">
                                    <p class="text-sm text-gray-500 mb-1">Preview:</p>
                                    <img :src="imageUrl" alt="Image Preview" class="max-w-full h-auto rounded shadow-md" style="max-height: 300px;">
                                </div>
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.portfolio-items.index') }}" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </a>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Update
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    function imagePreview() {
        return {
            uploadType: 'file',
            imageUrl: '{{ Str::startsWith($portfolioItem->image, "http") ? $portfolioItem->image : ($portfolioItem->image ? asset($portfolioItem->image) : "") }}',
            urlInput: '{{ filter_var($portfolioItem->image, FILTER_VALIDATE_URL) ? $portfolioItem->image : "" }}',
            
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

</x-app-layout>
