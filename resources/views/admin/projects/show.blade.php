<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Project Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <a href="{{ route('admin.projects.index') }}" title="Back"><button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-2 rounded mb-4">< Back</button></a>

                    <!-- Project Image -->
                    @if($project->image)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Project Image</h3>
                            <img src="{{ $project->image }}" alt="{{ $project->title }}" class="max-w-md rounded-lg shadow-md">
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">ID</h3>
                                <p class="mt-1 text-gray-900">{{ $project->id }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Title</h3>
                                <p class="mt-1 text-gray-900 font-semibold text-lg">{{ $project->title }}</p>
                            </div>

                            @if($project->tags && count($project->tags) > 0)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Tags</h3>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    @foreach($project->tags as $tag)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Live URL</h3>
                                @if($project->live_url)
                                    <a href="{{ $project->live_url }}" target="_blank" class="mt-1 text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                        {{ $project->live_url }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @else
                                    <p class="mt-1 text-gray-400 italic">Not provided</p>
                                @endif
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">GitHub URL</h3>
                                @if($project->github_url)
                                    <a href="{{ $project->github_url }}" target="_blank" class="mt-1 text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                        {{ $project->github_url }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @else
                                    <p class="mt-1 text-gray-400 italic">Not provided</p>
                                @endif
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">List Order</h3>
                                <p class="mt-1 text-gray-900">{{ $project->list_order ?? 0 }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Status</h3>
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $project->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Featured on Homepage</h3>
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->featured_homepage ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $project->featured_homepage ? 'Yes' : 'No' }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                                <p class="mt-1 text-gray-900">{{ $project->created_at->format('M d, Y H:i') }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Updated At</h3>
                                <p class="mt-1 text-gray-900">{{ $project->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($project->description)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Description</h3>
                        <div class="prose max-w-none bg-gray-50 p-4 rounded-lg">
                            {!! nl2br(e($project->description)) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Quick Links Section -->
                    @if($project->live_url || $project->github_url)
                    <div class="mt-6 flex gap-3">
                        @if($project->live_url)
                        <a href="{{ $project->live_url }}" target="_blank" class="inline-flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                            View Live Site
                        </a>
                        @endif
                        @if($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-700 to-gray-900 hover:from-gray-800 hover:to-black text-white font-bold py-2 px-4 rounded-lg shadow transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            View on GitHub
                        </a>
                        @endif
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-6 pt-6 border-t flex gap-3">
                        <a href="{{ route('admin.projects.edit', $project->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Project
                        </a>
                        <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this project?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete Project
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
