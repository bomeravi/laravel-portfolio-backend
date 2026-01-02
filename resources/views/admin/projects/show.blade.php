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

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>ID</th><td>{{ $project->id }}</td>
                                </tr>
                                <tr><th> Title </th><td> {{ $project->title }} </td></tr>
                                <tr><th> Description </th><td> {{ $project->description }} </td></tr>
                                 <tr><th> Image </th><td> {{ $project->image }} </td></tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
