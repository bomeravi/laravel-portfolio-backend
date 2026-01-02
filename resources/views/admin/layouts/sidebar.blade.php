<div class="flex flex-col w-64 h-full px-4 py-8 bg-white border-r dark:bg-gray-800 dark:border-gray-600">
    
    <div class="flex items-center justify-center">
        <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800 dark:text-white">
            AdminPanel
        </a>
    </div>

    <div class="flex flex-col justify-between flex-1 mt-6 overflow-y-auto">
        <nav>
            <a class="flex items-center px-4 py-2 text-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200' : '' }} rounded-md dark:bg-gray-700 dark:text-gray-200" href="{{ route('admin.dashboard') }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 11H5M19 11C20.1046 11 21 11.8954 21 13V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V13C3 11.8954 3.89543 11 5 11M19 11V9C19 7.89543 18.1046 7 17 7M5 11V9C5 7.89543 5.89543 7 7 7M7 7V5C7 3.89543 7.89543 3 9 3H15C16.1046 3 17 3.89543 17 5V7M7 7H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="mx-4 font-medium">Dashboard</span>
            </a>

            <div x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
                <a class="flex items-center px-4 py-2 mt-5 text-gray-600 transition-colors duration-200 transform rounded-md dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700" href="{{ route('admin.users.index') }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <path d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                         <path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="mx-4 font-medium">Users</span>
                </a>
            </div>

            <!-- Content Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('admin.blogs.*') || request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }" class="mt-5">
                <button @click="open = !open" class="flex items-center w-full px-4 py-2 text-gray-600 transition-colors duration-200 transform rounded-md dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <span class="mx-4 font-medium">Blog/Content</span>
                    <svg x-show="!open" class="w-4 h-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="open" class="w-4 h-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
                <div x-show="open" class="mt-2 space-y-1 pl-11" style="display: none;">
                    <a href="{{ route('admin.blogs.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.blogs.*') ? 'font-bold text-gray-900' : '' }}">Blogs</a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.categories.*') ? 'font-bold text-gray-900' : '' }}">Categories</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-400 cursor-not-allowed">Diaries (Soon)</a>
                </div>
            </div>

            <!-- Portfolio Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('admin.projects.*') || request()->routeIs('admin.portfolio-items.*') || request()->routeIs('admin.skills.*') || request()->routeIs('admin.experiences.*') ? 'true' : 'false' }} }" class="mt-5">
                 <button @click="open = !open" class="flex items-center w-full px-4 py-2 text-gray-600 transition-colors duration-200 transform rounded-md dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 focus:outline-none">
                     <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                     </svg>
                    <span class="mx-4 font-medium">Portfolio</span>
                    <svg x-show="!open" class="w-4 h-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="open" class="w-4 h-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                 </button>
                 <div x-show="open" class="mt-2 space-y-1 pl-11" style="display: none;">
                    <a href="{{ route('admin.projects.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.projects.*') ? 'font-bold text-gray-900' : '' }}">Projects</a>
                    <a href="{{ route('admin.portfolio-items.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.portfolio-items.*') ? 'font-bold text-gray-900' : '' }}">Items</a>
                    <a href="{{ route('admin.skills.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.skills.*') ? 'font-bold text-gray-900' : '' }}">Skills</a>
                    <a href="{{ route('admin.experiences.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.experiences.*') ? 'font-bold text-gray-900' : '' }}">Experience</a>
                 </div>
            </div>

            <!-- Messages -->
            <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center px-4 py-2 mt-5 text-gray-600 transition-colors duration-200 transform rounded-md dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 {{ request()->routeIs('admin.contact-messages.*') ? 'bg-gray-200 text-gray-900' : '' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="mx-4 font-medium">Messages</span>
            </a>

            <!-- Settings Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('admin.profile.*') ? 'true' : 'false' }} }" class="mt-5">
                 <button @click="open = !open" class="flex items-center w-full px-4 py-2 text-gray-600 transition-colors duration-200 transform rounded-md dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 focus:outline-none">
                     <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                     </svg>
                    <span class="mx-4 font-medium">Settings</span>
                    <svg x-show="!open" class="w-4 h-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <svg x-show="open" class="w-4 h-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                 </button>
                 <div x-show="open" class="mt-2 space-y-1 pl-11" style="display: none;">
                    <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-gray-900 {{ request()->routeIs('admin.profile.*') ? 'font-bold text-gray-900' : '' }}">Profile</a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
                            Log out
                        </button>
                    </form>
                 </div>
            </div>

        </nav>
    </div>
</div>
