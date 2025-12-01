<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900 font-sans antialiased selection:bg-blue-500 selection:text-white">
        <div class="min-h-screen flex">
            <!-- Left Side - Branding/Visual (hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
                <!-- Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700"></div>

                <!-- Pattern Overlay -->
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <defs>
                            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100" height="100" fill="url(#grid)" />
                    </svg>
                </div>

                <!-- Floating Elements -->
                <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 right-1/3 w-48 h-48 bg-blue-400/20 rounded-full blur-3xl"></div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group" wire:navigate>
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-serif font-bold text-2xl border border-white/20">
                            N
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-serif font-bold text-white tracking-tight leading-tight">
                                Transfer Portal
                            </span>
                            <span class="text-sm text-white/60 font-medium">Namibia</span>
                        </div>
                    </a>

                    <!-- Main Content -->
                    <div class="max-w-md">
                        <h1 class="text-4xl xl:text-5xl font-serif font-bold text-white mb-6 leading-tight">
                            Find Your Perfect Transfer Match
                        </h1>
                        <p class="text-lg text-white/80 leading-relaxed mb-8">
                            Connect with government colleagues for mutual transfers. You move to where they are, they move to where you are.
                        </p>

                        <!-- Features -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-white/90">Smart matching algorithm</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-white/90">All 14 regions covered</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-white/90">100% free to use</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Quote -->
                    <div class="mt-auto">
                        <blockquote class="border-l-2 border-white/30 pl-4">
                            <p class="text-white/70 italic">
                                "The portal made finding a transfer partner so much easier. Within a week, I found my perfect match!"
                            </p>
                            <footer class="mt-2 text-white/50 text-sm">
                                — Government Employee, Windhoek
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="flex-1 flex flex-col">
                <!-- Mobile Header -->
                <div class="lg:hidden p-6 border-b border-zinc-200 dark:border-zinc-800">
                    <a href="{{ route('home') }}" class="flex items-center gap-3" wire:navigate>
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-serif font-bold text-xl shadow-lg shadow-blue-500/20">
                            N
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-serif font-bold text-zinc-900 dark:text-white tracking-tight leading-tight">
                                Transfer Portal
                            </span>
                            <span class="text-xs text-zinc-500 font-medium">Namibia</span>
                        </div>
                    </a>
                </div>

                <!-- Form Container -->
                <div class="flex-1 flex items-center justify-center p-6 sm:p-8 lg:p-12">
                    <div class="w-full max-w-md">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 text-center lg:text-left border-t border-zinc-100 dark:border-zinc-800">
                    <p class="text-sm text-zinc-500 dark:text-zinc-500">
                        &copy; {{ date('Y') }} Nam Transfer Portal. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
