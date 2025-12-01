<?php

use App\Models\EmployeeProfile;
use App\Models\TransferRequest;
use App\Models\User;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('components.layouts.guest')]
#[Title('Employee Transfer Portal - Find Your Transfer Match')]
class extends Component {
    public function with(): array
    {
        return [
            'totalUsers' => User::whereNotNull('email_verified_at')->count(),
            'totalProfiles' => EmployeeProfile::count(),
            'totalConnections' => TransferRequest::where('status', 'accepted')->count(),
        ];
    }
}; ?>

<div class="relative">
    <!-- Hero Section -->
    <section class="relative min-h-[90vh] flex items-center justify-center px-4 overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-b from-blue-50/80 via-zinc-50 to-white dark:from-blue-950/30 dark:via-zinc-900 dark:to-zinc-900"></div>
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-400/20 dark:bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-indigo-400/20 dark:bg-indigo-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/3 right-1/3 w-64 h-64 bg-purple-400/10 dark:bg-purple-500/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-5xl mx-auto text-center relative z-10 py-20">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm border border-zinc-200/50 dark:border-zinc-700/50 shadow-sm mb-10">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                </span>
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Connecting Namibian Government Employees</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-serif font-bold text-zinc-900 dark:text-white mb-8 leading-[1.1] tracking-tight">
                Find Your Perfect
                <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-600 dark:from-blue-400 dark:via-indigo-400 dark:to-blue-400">
                    Transfer Match
                </span>
            </h1>

            <!-- Subheading -->
            <p class="text-lg sm:text-xl lg:text-2xl text-zinc-600 dark:text-zinc-400 mb-12 max-w-2xl mx-auto leading-relaxed font-light">
                Connect with colleagues for mutual transfers. You move to where they are, they move to where you are.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
                @auth
                    <a href="{{ route('dashboard') }}" class="group w-full sm:w-auto px-8 py-4 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-full hover:bg-zinc-800 dark:hover:bg-zinc-100 hover:shadow-2xl hover:shadow-zinc-900/20 dark:hover:shadow-white/20 hover:-translate-y-1 transition-all duration-300 text-lg font-semibold inline-flex items-center justify-center gap-2">
                        Go to Dashboard
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="group w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-full hover:from-blue-700 hover:to-indigo-700 hover:shadow-2xl hover:shadow-blue-500/30 hover:-translate-y-1 transition-all duration-300 text-lg font-semibold inline-flex items-center justify-center gap-2">
                        Get Started Free
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white rounded-full hover:bg-zinc-50 dark:hover:bg-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600 transition-all duration-300 text-lg font-semibold">
                        Sign In
                    </a>
                @endauth
            </div>

            <!-- Trust Indicators -->
            <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-4 text-zinc-500 dark:text-zinc-500">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="text-sm font-medium">100% Free</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="text-sm font-medium">Secure & Private</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="text-sm font-medium">All 14 Regions</span>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 rounded-full border-2 border-zinc-300 dark:border-zinc-700 flex items-start justify-center p-1">
                <div class="w-1.5 h-2.5 bg-zinc-400 dark:bg-zinc-600 rounded-full animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="relative py-20 bg-white dark:bg-zinc-900 border-y border-zinc-100 dark:border-zinc-800">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Stat 1 -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-500/5 dark:from-blue-500/10 dark:to-indigo-500/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-8 text-center">
                        <div class="text-5xl lg:text-6xl font-serif font-bold text-zinc-900 dark:text-white mb-3">
                            {{ number_format($totalUsers) }}
                        </div>
                        <div class="text-zinc-500 dark:text-zinc-400 font-medium uppercase tracking-wider text-sm">
                            Registered Users
                        </div>
                    </div>
                </div>

                <!-- Stat 2 -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 dark:from-indigo-500/10 dark:to-purple-500/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-8 text-center border-y md:border-y-0 md:border-x border-zinc-100 dark:border-zinc-800">
                        <div class="text-5xl lg:text-6xl font-serif font-bold text-zinc-900 dark:text-white mb-3">
                            {{ number_format($totalProfiles) }}
                        </div>
                        <div class="text-zinc-500 dark:text-zinc-400 font-medium uppercase tracking-wider text-sm">
                            Active Profiles
                        </div>
                    </div>
                </div>

                <!-- Stat 3 -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 dark:from-purple-500/10 dark:to-pink-500/10 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative p-8 text-center">
                        <div class="text-5xl lg:text-6xl font-serif font-bold text-zinc-900 dark:text-white mb-3">
                            {{ number_format($totalConnections) }}
                        </div>
                        <div class="text-zinc-500 dark:text-zinc-400 font-medium uppercase tracking-wider text-sm">
                            Successful Matches
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-24 lg:py-32 px-4 bg-zinc-50 dark:bg-zinc-900/50">
        <div class="max-w-6xl mx-auto">
            <!-- Section Header -->
            <div class="text-center mb-20">
                <span class="inline-block px-4 py-1.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-semibold mb-6">
                    Simple Process
                </span>
                <h2 class="text-4xl lg:text-5xl font-serif font-bold text-zinc-900 dark:text-white mb-6">
                    How It Works
                </h2>
                <p class="text-xl text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
                    Three simple steps to your next career move
                </p>
            </div>

            <!-- Steps -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-6">
                <!-- Step 1 -->
                <div class="relative group">
                    <div class="bg-white dark:bg-zinc-800/50 rounded-3xl p-8 lg:p-10 border border-zinc-100 dark:border-zinc-800 hover:border-blue-200 dark:hover:border-blue-900/50 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-500 h-full">
                        <!-- Step Number -->
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-blue-500/30">
                                1
                            </div>
                            <div class="hidden lg:block flex-1 h-px bg-gradient-to-r from-blue-200 to-transparent dark:from-blue-800"></div>
                        </div>

                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Create Your Profile</h3>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Sign up securely and set up your profile with your current location, ministry, grade, and where you'd like to transfer to.
                        </p>

                        <!-- Visual -->
                        <div class="mt-8 p-4 bg-zinc-50 dark:bg-zinc-900/50 rounded-xl border border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-zinc-200 dark:bg-zinc-700"></div>
                                <div class="flex-1">
                                    <div class="h-3 w-24 bg-zinc-200 dark:bg-zinc-700 rounded mb-1.5"></div>
                                    <div class="h-2 w-16 bg-zinc-100 dark:bg-zinc-800 rounded"></div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="h-2 w-full bg-zinc-100 dark:bg-zinc-800 rounded"></div>
                                <div class="h-2 w-3/4 bg-zinc-100 dark:bg-zinc-800 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative group">
                    <div class="bg-white dark:bg-zinc-800/50 rounded-3xl p-8 lg:p-10 border border-zinc-100 dark:border-zinc-800 hover:border-indigo-200 dark:hover:border-indigo-900/50 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-500 h-full">
                        <!-- Step Number -->
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-indigo-500/30">
                                2
                            </div>
                            <div class="hidden lg:block flex-1 h-px bg-gradient-to-r from-indigo-200 to-transparent dark:from-indigo-800"></div>
                        </div>

                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Find Your Match</h3>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Our smart matching algorithm instantly finds employees who want to move to your location while you want theirs.
                        </p>

                        <!-- Visual -->
                        <div class="mt-8 p-4 bg-zinc-50 dark:bg-zinc-900/50 rounded-xl border border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                                    </div>
                                    <span class="text-xs text-zinc-500">You</span>
                                </div>
                                <div class="flex-1 mx-3 flex items-center gap-1">
                                    <div class="h-px flex-1 bg-gradient-to-r from-blue-300 to-indigo-300 dark:from-blue-700 dark:to-indigo-700"></div>
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    <div class="h-px flex-1 bg-gradient-to-r from-indigo-300 to-purple-300 dark:from-indigo-700 dark:to-purple-700"></div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-zinc-500">Match</span>
                                    <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                        <div class="w-4 h-4 rounded-full bg-purple-500"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative group">
                    <div class="bg-white dark:bg-zinc-800/50 rounded-3xl p-8 lg:p-10 border border-zinc-100 dark:border-zinc-800 hover:border-purple-200 dark:hover:border-purple-900/50 hover:shadow-xl hover:shadow-purple-500/5 transition-all duration-500 h-full">
                        <!-- Step Number -->
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-purple-500/30">
                                3
                            </div>
                        </div>

                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Connect & Transfer</h3>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Send connection requests, exchange contact details after mutual consent, and coordinate the transfer through HR.
                        </p>

                        <!-- Visual -->
                        <div class="mt-8 p-4 bg-zinc-50 dark:bg-zinc-900/50 rounded-xl border border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center justify-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="text-sm font-medium text-green-600 dark:text-green-400">Connected!</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 lg:py-32 px-4 bg-white dark:bg-zinc-900">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                <!-- Content -->
                <div>
                    <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-sm font-semibold mb-6">
                        Why Choose Us
                    </span>
                    <h2 class="text-4xl lg:text-5xl font-serif font-bold text-zinc-900 dark:text-white mb-6 leading-tight">
                        Built for Government Professionals
                    </h2>
                    <p class="text-xl text-zinc-600 dark:text-zinc-400 mb-12 leading-relaxed">
                        We understand the unique needs of public service transfers. Our platform makes the process transparent, fair, and efficient.
                    </p>

                    <!-- Feature List -->
                    <div class="space-y-8">
                        <div class="flex gap-5">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">Smart Matching</h3>
                                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Automatically finds the best transfer opportunities based on ministry, grade, and location preferences.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-5">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">Privacy First</h3>
                                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Your contact details are protected and only shared after both parties consent to connect.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-5">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">Nationwide Coverage</h3>
                                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    All 14 regions and every major town in Namibia, ensuring you can find matches wherever you need.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visual -->
                <div class="relative">
                    <!-- Background Glow -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-indigo-500/10 to-purple-500/10 dark:from-blue-500/5 dark:via-indigo-500/5 dark:to-purple-500/5 rounded-3xl blur-2xl"></div>

                    <!-- Card Stack -->
                    <div class="relative bg-zinc-100 dark:bg-zinc-800 rounded-3xl p-6 lg:p-8 border border-zinc-200 dark:border-zinc-700">
                        <!-- Main Card -->
                        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-lg border border-zinc-100 dark:border-zinc-800 mb-4">
                            <div class="flex items-start gap-4 mb-5">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                                    MN
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-zinc-900 dark:text-white">Maria Nangolo</span>
                                        <span class="px-2 py-0.5 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full">98% Match</span>
                                    </div>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Ministry of Education</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-zinc-600 dark:text-zinc-400 mb-5">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span>Windhoek</span>
                                </div>
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span>Oshakati</span>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-xl">
                                    Connect
                                </button>
                                <button class="px-4 py-2.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 text-sm font-medium rounded-xl">
                                    View
                                </button>
                            </div>
                        </div>

                        <!-- Background Card -->
                        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-5 shadow-sm border border-zinc-100 dark:border-zinc-800 opacity-60 scale-[0.97] -translate-y-2">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-zinc-200 dark:bg-zinc-700"></div>
                                <div>
                                    <div class="h-3 w-28 bg-zinc-200 dark:bg-zinc-700 rounded mb-2"></div>
                                    <div class="h-2 w-20 bg-zinc-100 dark:bg-zinc-800 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative py-24 lg:py-32 px-4 overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-900 to-zinc-800 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950"></div>
        <div class="absolute inset-0">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-indigo-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-4xl mx-auto text-center relative z-10">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-white/80 text-sm font-semibold mb-8 backdrop-blur-sm border border-white/10">
                Start Today
            </span>
            <h2 class="text-4xl lg:text-5xl xl:text-6xl font-serif font-bold text-white mb-8 leading-tight">
                Ready to Find Your <br class="hidden sm:block" />Transfer Match?
            </h2>
            <p class="text-xl text-zinc-400 mb-12 max-w-2xl mx-auto leading-relaxed">
                Join Namibian government employees who are already exploring new opportunities across the country.
            </p>
            @guest
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-zinc-900 rounded-full hover:bg-zinc-100 hover:shadow-2xl hover:shadow-white/20 transition-all duration-300 text-lg font-bold">
                        Create Your Profile
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                    <a href="{{ route('about') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border border-white/20 text-white rounded-full hover:bg-white/10 hover:border-white/30 transition-all duration-300 text-lg font-semibold">
                        Learn More
                    </a>
                </div>
            @endguest
            @auth
                <a href="{{ route('dashboard') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-zinc-900 rounded-full hover:bg-zinc-100 hover:shadow-2xl hover:shadow-white/20 transition-all duration-300 text-lg font-bold">
                    Go to Dashboard
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            @endauth
        </div>
    </section>
</div>
