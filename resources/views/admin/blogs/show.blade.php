<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <a href="{{ route('admin.blogs.index') }}" title="Back"><button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-2 rounded mb-4">< Back</button></a>

                    <!-- Featured Image -->
                    @if($blog->featured_image)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Featured Image</h3>
                            <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}" class="max-w-md rounded-lg shadow-md">
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">ID</h3>
                                <p class="mt-1 text-gray-900">{{ $blog->id }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Title</h3>
                                <p class="mt-1 text-gray-900 font-semibold">{{ $blog->title }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Slug</h3>
                                <p class="mt-1 text-gray-900">{{ $blog->slug }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Author</h3>
                                <p class="mt-1 text-gray-900">{{ $blog->user->name ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Category</h3>
                                <p class="mt-1 text-gray-900">{{ $blog->category->name ?? 'N/A' }}</p>
                            </div>

                            @if($blog->tags && $blog->tags->count() > 0)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Tags</h3>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    @foreach($blog->tags as $tag)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Reading Time</h3>
                                <p class="mt-1 text-gray-900">{{ $blog->reading_time ?? 'N/A' }} min</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Status</h3>
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $blog->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $blog->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Published</h3>
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $blog->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $blog->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Published At</h3>
                                <p class="mt-1 text-gray-900">{{ $blog->published_at ? $blog->published_at->format('M d, Y H:i') : 'Not published' }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Featured on Homepage</h3>
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $blog->featured_homepage ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $blog->featured_homepage ? 'Yes' : 'No' }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                                <p class="mt-1 text-gray-900">{{ $blog->created_at->format('M d, Y H:i') }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Updated At</h3>
                                <p class="mt-1 text-gray-900">{{ $blog->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Excerpt -->
                    @if($blog->excerpt)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Excerpt</h3>
                        <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $blog->excerpt }}</p>
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Content</h3>
                        <div class="prose max-w-none bg-gray-50 p-4 rounded-lg">
                            {!! $blog->content !!}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex gap-3">
                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Blog
                        </a>
                        <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this blog?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete Blog
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
