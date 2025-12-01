<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Employee Transfer Portal' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-white dark:bg-zinc-900 font-sans antialiased selection:bg-blue-500 selection:text-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" x-data="{ scrolled: false, mobileOpen: false }" @scroll.window="scrolled = window.scrollY > 20">
        <div
            class="absolute inset-0 transition-all duration-300"
            :class="scrolled ? 'bg-white/90 dark:bg-zinc-900/90 backdrop-blur-xl border-b border-zinc-200/50 dark:border-zinc-800/50 shadow-sm' : 'bg-transparent'"
        ></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group" wire:navigate>
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-serif font-bold text-xl shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/40 group-hover:scale-105 transition-all duration-300">
                            N
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-serif font-bold text-zinc-900 dark:text-white tracking-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors leading-tight">
                                Transfer Portal
                            </span>
                            <span class="text-xs text-zinc-500 dark:text-zinc-500 font-medium hidden sm:block">Namibia</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" wire:navigate class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                        Home
                    </a>
                    <a href="{{ route('about') }}" wire:navigate class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('about') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                        About
                    </a>
                    <a href="{{ route('faq') }}" wire:navigate class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('faq') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                        FAQ
                    </a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" wire:navigate class="hidden sm:inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full hover:from-blue-700 hover:to-indigo-700 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="hidden sm:block px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full hover:from-blue-700 hover:to-indigo-700 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300">
                            Get Started
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button
                        @click="mobileOpen = !mobileOpen"
                        class="md:hidden p-2 rounded-lg text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                    >
                        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div
                x-show="mobileOpen"
                x-collapse
                x-cloak
                class="md:hidden pb-4"
            >
                <div class="flex flex-col gap-1 pt-2 border-t border-zinc-200 dark:border-zinc-800">
                    <a href="{{ route('home') }}" wire:navigate class="px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                        Home
                    </a>
                    <a href="{{ route('about') }}" wire:navigate class="px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('about') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                        About
                    </a>
                    <a href="{{ route('faq') }}" wire:navigate class="px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('faq') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                        FAQ
                    </a>
                    @guest
                        <a href="{{ route('login') }}" wire:navigate class="px-4 py-3 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                            Sign In
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Footer -->
            <div class="py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                    <!-- Brand -->
                    <div class="lg:col-span-2">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 mb-6" wire:navigate>
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-serif font-bold text-xl shadow-lg shadow-blue-500/20">
                                N
                            </div>
                            <div class="flex flex-col">
                                <span class="text-lg font-serif font-bold text-zinc-900 dark:text-white tracking-tight leading-tight">
                                    Transfer Portal
                                </span>
                                <span class="text-xs text-zinc-500 dark:text-zinc-500 font-medium">Namibia</span>
                            </div>
                        </a>
                        <p class="text-zinc-600 dark:text-zinc-400 max-w-md leading-relaxed mb-6">
                            Facilitating seamless mutual transfers for Namibian government employees. Connect with colleagues, find your match, and move with confidence.
                        </p>
                        <div class="flex items-center gap-4">
                            <a href="mailto:support@transferportal.gov.na" class="inline-flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                support@transferportal.gov.na
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wider mb-4">Platform</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ route('home') }}" wire:navigate class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('about') }}" wire:navigate class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    About Us
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('faq') }}" wire:navigate class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    FAQ
                                </a>
                            </li>
                            @guest
                                <li>
                                    <a href="{{ route('register') }}" wire:navigate class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        Get Started
                                    </a>
                                </li>
                            @endguest
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wider mb-4">Legal</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="#" class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    Privacy Policy
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    Terms of Service
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                    Cookie Policy
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="py-6 border-t border-zinc-200 dark:border-zinc-800">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-zinc-500 dark:text-zinc-500">
                        &copy; {{ date('Y') }} Nam Transfer Portal. All rights reserved.
                    </p>
                    <div class="flex items-center gap-6">
                        <span class="text-xs text-zinc-400 dark:text-zinc-600">
                            Built for Namibian Government Employees
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @fluxScripts
</body>
</html>
