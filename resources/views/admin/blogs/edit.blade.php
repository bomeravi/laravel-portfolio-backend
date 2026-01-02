<x-app-layout>
    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 5px 12px;
        }
    </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Blog') }}
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

                    <form method="POST" action="{{ route('admin.blogs.update', $blog->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <!-- Left Column: Main Content (70%) -->
                            <div class="md:col-span-8">
                                <div class="mb-4">
                                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title: </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="title" type="text" id="title" value="{{ old('title', $blog->title) }}" required>
                                </div>

                                <div class="mb-4">
                                    <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">Slug: </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="slug" type="text" id="slug" value="{{ old('slug', $blog->slug) }}" required>
                                </div>

                                <div class="mb-4">
                                     <label for="excerpt" class="block text-gray-700 text-sm font-bold mb-2">Excerpt: </label>
                                     <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="excerpt" id="excerpt" rows="3">{{ old('excerpt', $blog->excerpt) }}</textarea>
                                </div>

                                <div class="mb-4">
                                     <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content: </label>
                                     <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="content" id="content" rows="15">{{ old('content', $blog->content) }}</textarea>
                                </div>
                            </div>

                            <!-- Right Column: Sidebar (30%) -->
                            <div class="md:col-span-4">
                                <div class="p-4 bg-gray-50 rounded-lg shadow-sm mb-4">
                                    <h3 class="text-lg font-bold text-gray-700 border-b pb-2 mb-3">Publishing Actions</h3>
                                    <div class="flex flex-col space-y-2">
                                        <div class="flex items-center mb-1">
                                            <input type="checkbox" name="is_published" id="is_published" class="form-checkbox h-5 w-5 text-blue-600" {{ old('is_published', $blog->is_published) ? 'checked' : '' }}>
                                            <label for="is_published" class="ml-2 text-gray-700">Is Published</label>
                                        </div>
                                        <div class="flex items-center mb-1">
                                            <input type="checkbox" name="status" id="status" class="form-checkbox h-5 w-5 text-green-600" {{ old('status', $blog->status) ? 'checked' : '' }}>
                                            <label for="status" class="ml-2 text-gray-700">Active Status</label>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="featured_homepage" id="featured_homepage" class="form-checkbox h-5 w-5 text-yellow-600" {{ old('featured_homepage', $blog->featured_homepage) ? 'checked' : '' }}>
                                            <label for="featured_homepage" class="ml-2 text-gray-700">Back Featured</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-lg shadow-sm mb-4">
                                    <h3 class="text-lg font-bold text-gray-700 border-b pb-2 mb-3">Category & Tags</h3>
                                    <div class="mb-4">
                                         <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category: </label>
                                         <select name="category_id" id="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ (old('category_id', $blog->category_id) == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                         </select>
                                    </div>

                                    <div class="mb-4">
                                        <label for="tags" class="block text-gray-700 text-sm font-bold mb-2">Tags: </label>
                                        <select name="tags[]" id="tags" multiple class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-full content-center">
                                            @foreach($tags as $tag)
                                                <option value="{{ $tag->id }}" {{ (collect(old('tags', $blog->tags->pluck('id')->toArray()))->contains($tag->id)) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                                    <h3 class="text-lg font-bold text-gray-700 border-b pb-2 mb-3">Featured Image</h3>
                                    <div x-data="imagePreview()">
                                        <!-- Image Preview Preview -->
                                        <div class="mb-3 bg-gray-200 h-48 rounded-lg flex items-center justify-center overflow-hidden border">
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
                                                <label for="upload_file" class="text-xs font-bold uppercase text-gray-600 cursor-pointer">Upload</label>
                                                
                                                <input type="radio" id="upload_url" x-model="uploadType" value="url" class="ml-4 mr-2">
                                                <label for="upload_url" class="text-xs font-bold uppercase text-gray-600 cursor-pointer">From URL</label>
                                            </div>
                                        </div>
                                        
                                        <!-- File Upload -->
                                        <div x-show="uploadType === 'file'">
                                            <input type="file" name="featured_image_file" @change="fileChosen" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                                        </div>
                                        
                                        <!-- URL Input -->
                                        <div x-show="uploadType === 'url'">
                                            <input type="url" name="featured_image_url" x-model="urlInput" @input="urlChanged" placeholder="https://example.com/image.jpg" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- Bottom Actions -->
                        <div class="mt-6 flex items-center justify-end border-t border-gray-200 pt-6">
                            <a href="{{ route('admin.blogs.index') }}" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </a>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Update Blog
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#tags').select2({
                placeholder: "Select tags",
                allowClear: true,
                width: '100%'
            });
            $('#category_id').select2({
                 placeholder: "Select Category",
                 allowClear: true,
                 width: '100%'
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            ClassicEditor
                .create(document.querySelector('#content'), {
                    simpleUpload: {
                        uploadUrl: '{{ route('admin.blogs.uploadImage') }}',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        });

        function imagePreview() {
            return {
                uploadType: 'file', // Default to file
                imageUrl: '{{ Str::startsWith($blog->featured_image, "http") ? $blog->featured_image : ($blog->featured_image ? asset($blog->featured_image) : "") }}',
                urlInput: '{{ filter_var($blog->featured_image, FILTER_VALIDATE_URL) ? $blog->featured_image : "" }}',
                
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
