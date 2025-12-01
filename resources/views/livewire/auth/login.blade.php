<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Welcome back')" :description="__('Sign in to your account to continue')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-5 h-5 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mt-0.5">
                        <svg class="w-3 h-3 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Username or Email -->
            <div>
                <flux:input
                    name="login"
                    :label="__('Username or Email')"
                    :value="old('login')"
                    type="text"
                    required
                    autofocus
                    autocomplete="username"
                    :placeholder="__('Enter your username or email')"
                />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        {{ __('Password') }}
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>
                <flux:input
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Enter your password')"
                    viewable
                />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2">
                <flux:checkbox name="remember" :checked="old('remember')" />
                <label for="remember" class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Remember me for 30 days') }}
                </label>
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    data-test="login-button"
                    class="w-full px-6 py-3 text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 hover:shadow-lg hover:shadow-blue-500/20 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition-all duration-200"
                >
                    {{ __('Sign in') }}
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-zinc-200 dark:border-zinc-800"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white dark:bg-zinc-900 text-zinc-500 dark:text-zinc-500">
                    {{ __('New to Transfer Portal?') }}
                </span>
            </div>
        </div>

        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                wire:navigate
                class="w-full px-6 py-3 text-base font-semibold text-center text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 border border-zinc-200 dark:border-zinc-700 transition-all duration-200"
            >
                {{ __('Create an account') }}
            </a>
        @endif
    </div>
</x-layouts.auth>
