<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Experience') }}
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

                    <form method="POST" action="{{ route('admin.experiences.update', $experience->id) }}" accept-charset="UTF-8" class="form-horizontal">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}

                        <div class="grid grid-cols-1 gap-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title: </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="title" type="text" id="title" value="{{ old('title', $experience->title) }}" required>
                                </div>
                                <div>
                                    <label for="company" class="block text-gray-700 text-sm font-bold mb-2">Company: </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="company" type="text" id="company" value="{{ old('company', $experience->company) }}" required>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Start Date: </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="start_date" type="date" id="start_date" value="{{ old('start_date', optional($experience->start_date)->format('Y-m-d')) }}" required>
                                </div>
                                <div>
                                    <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">End Date: </label>
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="end_date" type="date" id="end_date" value="{{ old('end_date', optional($experience->end_date)->format('Y-m-d')) }}">
                                    <p class="text-xs text-gray-500 mt-1">Leave blank if currently working here.</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="current" id="current" class="form-checkbox h-5 w-5 text-blue-600" {{ old('current', $experience->current) ? 'checked' : '' }}>
                                        <label for="current" class="ml-2 text-gray-700">Currently Working</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="display_month" id="display_month" class="form-checkbox h-5 w-5 text-blue-600" {{ old('display_month', $experience->display_month) ? 'checked' : '' }}>
                                        <label for="display_month" class="ml-2 text-gray-700">Display Month</label>
                                    </div>
                                     <div class="flex items-center">
                                        <input type="checkbox" name="status" id="status" class="form-checkbox h-5 w-5 text-blue-600" {{ old('status', $experience->status) ? 'checked' : '' }}>
                                        <label for="status" class="ml-2 text-gray-700">Active</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                 <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description: </label>
                                 <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="description" id="description" rows="3">{{ old('description', $experience->description) }}</textarea>
                            </div>

                            <div class="mb-4">
                                 <label for="achievements" class="block text-gray-700 text-sm font-bold mb-2">Achievements (One per line): </label>
                                 <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="achievements" id="achievements" rows="4">{{ old('achievements', is_array($experience->achievements) ? implode("\n", $experience->achievements) : $experience->achievements) }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.experiences.index') }}" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
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
</x-app-layout>
