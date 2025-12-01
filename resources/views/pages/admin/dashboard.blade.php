<x-layouts.app>
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Admin Dashboard</h1>
            <p class="mt-1 text-zinc-600 dark:text-zinc-400">Manage the Employee Transfer Portal</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <flux:icon name="users" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ \App\Models\User::count() }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Users</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <flux:icon name="identification" class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ \App\Models\EmployeeProfile::count() }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Profiles</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <flux:icon name="arrow-path" class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ \App\Models\TransferRequest::pending()->count() }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Pending Requests</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <flux:icon name="check-circle" class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ \App\Models\TransferRequest::accepted()->count() }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Accepted</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="/admin" class="block bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <flux:icon name="cog-6-tooth" class="w-8 h-8 text-indigo-600 dark:text-indigo-400" />
                    </div>
                </div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Admin Panel</h2>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm">
                    Access the full Filament admin panel to manage users, profiles, regions, employers, and more.
                </p>
            </a>

            <a href="{{ route('home') }}" class="block bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <flux:icon name="home" class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">View Site</h2>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm">
                    View the public-facing website as visitors see it.
                </p>
            </a>

            <a href="{{ route('settings.edit') }}" class="block bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-zinc-100 dark:bg-zinc-800 rounded-lg">
                        <flux:icon name="user-circle" class="w-8 h-8 text-zinc-600 dark:text-zinc-400" />
                    </div>
                </div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Account Settings</h2>
                <p class="text-zinc-600 dark:text-zinc-400 text-sm">
                    Manage your account settings, password, and preferences.
                </p>
            </a>
        </div>
    </div>
</x-layouts.app>
