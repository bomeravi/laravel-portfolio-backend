<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('flash_message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('flash_message') }}</span>
                </div>
            @endif

            @if ($errors->any())
                 <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Profile Information -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Profile Information') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Update your account's profile information and email address.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('admin.profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                                <input id="name" name="name" type="text" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                            </div>

                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                <input id="email" name="email" type="email" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                            </div>

                            <div>
                                <label class="block font-medium text-sm text-gray-700">Avatar</label>
                                
                                <!-- Current Avatar & Preview -->
                                <div class="mt-2 mb-4 flex items-center gap-4">
                                    @if($user->avatar)
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">Current</p>
                                            <img src="{{ $user->avatar }}" alt="Current Avatar" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                        </div>
                                    @endif
                                    <div id="avatar-preview-container" class="hidden">
                                        <p class="text-xs text-gray-500 mb-1">New Preview</p>
                                        <img id="avatar-preview" src="" alt="Avatar Preview" class="w-20 h-20 rounded-full object-cover border-2 border-indigo-400 shadow-md">
                                    </div>
                                </div>
                                
                                <!-- Avatar Source Toggle -->
                                <div class="mt-2 mb-3">
                                    <label class="inline-flex items-center mr-4">
                                        <input type="radio" name="avatar_source" value="file" class="form-radio text-indigo-600" checked onclick="toggleAvatarInput('file')">
                                        <span class="ml-2 text-sm text-gray-700">Upload File</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="avatar_source" value="url" class="form-radio text-indigo-600" onclick="toggleAvatarInput('url')">
                                        <span class="ml-2 text-sm text-gray-700">Enter URL</span>
                                    </label>
                                </div>

                                <!-- File Upload Input -->
                                <div id="avatar-file-input">
                                    <input id="avatar_file" name="avatar_file" type="file" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm" accept="image/*" onchange="previewFileImage(this)" />
                                    <p class="mt-1 text-xs text-gray-500">Max file size: 2MB. Supported formats: JPG, PNG, GIF</p>
                                </div>

                                <!-- URL Input -->
                                <div id="avatar-url-input" class="hidden">
                                    <input id="avatar_url" name="avatar_url" type="url" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" placeholder="https://example.com/avatar.jpg" value="{{ old('avatar_url') }}" oninput="previewUrlImage(this.value)" />
                                    <p class="mt-1 text-xs text-gray-500">Enter a valid image URL</p>
                                    <p id="url-error" class="mt-1 text-xs text-red-500 hidden">Unable to load image from this URL</p>
                                </div>
                            </div>

                            <script>
                                function toggleAvatarInput(type) {
                                    const fileInput = document.getElementById('avatar-file-input');
                                    const urlInput = document.getElementById('avatar-url-input');
                                    const previewContainer = document.getElementById('avatar-preview-container');
                                    
                                    // Hide preview when switching
                                    previewContainer.classList.add('hidden');
                                    document.getElementById('url-error').classList.add('hidden');
                                    
                                    if (type === 'file') {
                                        fileInput.classList.remove('hidden');
                                        urlInput.classList.add('hidden');
                                        document.getElementById('avatar_url').value = '';
                                    } else {
                                        fileInput.classList.add('hidden');
                                        urlInput.classList.remove('hidden');
                                        document.getElementById('avatar_file').value = '';
                                    }
                                }

                                function previewFileImage(input) {
                                    const previewContainer = document.getElementById('avatar-preview-container');
                                    const preview = document.getElementById('avatar-preview');
                                    
                                    if (input.files && input.files[0]) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            preview.src = e.target.result;
                                            previewContainer.classList.remove('hidden');
                                        };
                                        reader.readAsDataURL(input.files[0]);
                                    } else {
                                        previewContainer.classList.add('hidden');
                                    }
                                }

                                let urlDebounceTimer;
                                function previewUrlImage(url) {
                                    const previewContainer = document.getElementById('avatar-preview-container');
                                    const preview = document.getElementById('avatar-preview');
                                    const urlError = document.getElementById('url-error');
                                    
                                    clearTimeout(urlDebounceTimer);
                                    urlError.classList.add('hidden');
                                    
                                    if (!url || url.trim() === '') {
                                        previewContainer.classList.add('hidden');
                                        return;
                                    }
                                    
                                    // Debounce to avoid too many requests
                                    urlDebounceTimer = setTimeout(function() {
                                        // Create a test image to verify URL is valid
                                        const testImg = new Image();
                                        testImg.onload = function() {
                                            preview.src = url;
                                            previewContainer.classList.remove('hidden');
                                            urlError.classList.add('hidden');
                                        };
                                        testImg.onerror = function() {
                                            previewContainer.classList.add('hidden');
                                            urlError.classList.remove('hidden');
                                        };
                                        testImg.src = url;
                                    }, 500);
                                }
                            </script>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Update Password') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Ensure your account is using a long, random password to stay secure.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('admin.profile.password') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <label for="current_password" class="block font-medium text-sm text-gray-700">Current Password</label>
                                <input id="current_password" name="current_password" type="password" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" autocomplete="current-password" />
                            </div>

                            <div>
                                <label for="password" class="block font-medium text-sm text-gray-700">New Password</label>
                                <input id="password" name="password" type="password" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" autocomplete="new-password" />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" autocomplete="new-password" />
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
