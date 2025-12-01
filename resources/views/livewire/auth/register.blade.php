<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create your account')" :description="__('Join thousands of government employees finding their perfect transfer match')" />

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

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Username -->
            <div>
                <flux:input
                    name="username"
                    :label="__('Username')"
                    :value="old('username')"
                    type="text"
                    required
                    autofocus
                    autocomplete="username"
                    :placeholder="__('Choose a username')"
                />
                <p class="mt-1.5 text-xs text-zinc-500 dark:text-zinc-500">
                    {{ __('3-50 characters, letters and numbers only') }}
                </p>
            </div>

            <!-- Name -->
            <div>
                <flux:input
                    name="name"
                    :label="__('Full Name')"
                    :value="old('name')"
                    type="text"
                    required
                    autocomplete="name"
                    :placeholder="__('Your full name')"
                />
            </div>

            <!-- Email Address -->
            <div>
                <flux:input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="you@example.com"
                />
            </div>

            <!-- Password -->
            <div>
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Create a strong password')"
                    viewable
                />
                <p class="mt-1.5 text-xs text-zinc-500 dark:text-zinc-500">
                    {{ __('Minimum 8 characters') }}
                </p>
            </div>

            <!-- Confirm Password -->
            <div>
                <flux:input
                    name="password_confirmation"
                    :label="__('Confirm password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Confirm your password')"
                    viewable
                />
            </div>

            <!-- Terms Notice -->
            <p class="text-xs text-zinc-500 dark:text-zinc-500 leading-relaxed">
                {{ __('By creating an account, you agree to our') }}
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Terms of Service') }}</a>
                {{ __('and') }}
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Privacy Policy') }}</a>.
            </p>

            <div class="pt-2">
                <button
                    type="submit"
                    data-test="register-user-button"
                    class="w-full px-6 py-3 text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 hover:shadow-lg hover:shadow-blue-500/20 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 transition-all duration-200"
                >
                    {{ __('Create account') }}
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
                    {{ __('Already have an account?') }}
                </span>
            </div>
        </div>

        <a
            href="{{ route('login') }}"
            wire:navigate
            class="w-full px-6 py-3 text-base font-semibold text-center text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 rounded-xl hover:bg-zinc-200 dark:hover:bg-zinc-700 border border-zinc-200 dark:border-zinc-700 transition-all duration-200"
        >
            {{ __('Sign in instead') }}
        </a>
    </div>
</x-layouts.auth>
