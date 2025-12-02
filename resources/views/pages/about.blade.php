<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('components.layouts.guest')]
#[Title('About - Employee Transfer Portal')]
class extends Component {
}; ?>

<div class="bg-white dark:bg-zinc-900">
    <!-- Hero Header -->
    <section class="relative py-24 lg:py-32 overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-b from-blue-50/50 via-zinc-50 to-white dark:from-blue-950/20 dark:via-zinc-900 dark:to-zinc-900"></div>
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-400/10 dark:bg-blue-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-indigo-400/10 dark:bg-indigo-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-4xl mx-auto px-4 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-semibold mb-6">
                About Us
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-zinc-900 dark:text-white mb-6 leading-tight">
                Facilitating Career Mobility
            </h1>
            <p class="text-xl text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                Empowering Namibia's public service workforce to find their ideal work location through seamless mutual transfers.
            </p>
        </div>
    </section>

    <main class="pb-24">
            <!-- Mission Section -->
            <section class="py-16 px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                        <div>
                            <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-sm font-semibold mb-6">
                                Our Mission
                            </span>
                            <h2 class="text-3xl lg:text-4xl font-serif font-bold text-zinc-900 dark:text-white mb-6 leading-tight">
                                Simplifying the Transfer Process
                            </h2>
                            <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">
                                The Employee Transfer Portal is an initiative designed to streamline the mutual transfer process for Namibian government employees. We aim to simplify the complexity of finding transfer partners by connecting colleagues with complementary relocation needs across the country.
                            </p>
                            <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Whether you need to move closer to family, explore new professional opportunities, or simply experience a different part of Namibia, we're here to help you find your perfect match.
                            </p>
                        </div>

                        <!-- Visual Card -->
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 dark:from-blue-500/5 dark:to-indigo-500/5 rounded-3xl blur-2xl"></div>
                            <div class="relative grid grid-cols-2 gap-4">
                                <div class="bg-white dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800">
                                    <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <h3 class="font-bold text-zinc-900 dark:text-white mb-2">Connecting People</h3>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Find colleagues who share your relocation goals</p>
                                </div>
                                <div class="bg-white dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800">
                                    <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mb-4">
                                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    </div>
                                    <h3 class="font-bold text-zinc-900 dark:text-white mb-2">Fast Matching</h3>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Instantly find compatible transfer partners</p>
                                </div>
                                <div class="bg-white dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800">
                                    <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-4">
                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    </div>
                                    <h3 class="font-bold text-zinc-900 dark:text-white mb-2">Secure Platform</h3>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Your data is protected at all times</p>
                                </div>
                                <div class="bg-white dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800">
                                    <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center mb-4">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h3 class="font-bold text-zinc-900 dark:text-white mb-2">100% Free</h3>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">No fees or hidden costs ever</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works Section -->
            <section class="py-16 px-4 bg-zinc-50 dark:bg-zinc-900/50">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-16">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-sm font-semibold mb-6">
                            The Process
                        </span>
                        <h2 class="text-3xl lg:text-4xl font-serif font-bold text-zinc-900 dark:text-white mb-4">
                            How It Works
                        </h2>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
                            Our portal uses a smart matching system that connects employees based on their current locations and preferred destinations.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <!-- Step 1 -->
                        <div class="flex gap-6 items-start bg-white dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/20">
                                1
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">Create Your Profile</h3>
                                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Sign up and enter your current details including your ministry, grade, location, and where you'd like to transfer to. Your profile helps us find the best matches for you.
                                </p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex gap-6 items-start bg-white dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/20">
                                2
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">Find Your Match</h3>
                                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Browse potential swap partners who match your criteria. Our algorithm shows you employees who want to move to your location while you want to move to theirs.
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex gap-6 items-start bg-white dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-purple-500/20">
                                3
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">Connect Securely</h3>
                                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Send a connection request to your match. Once they accept, you can exchange contact details and discuss the transfer. Your information remains private until mutual consent.
                                </p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex gap-6 items-start bg-white dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-bold shadow-lg shadow-green-500/20">
                                4
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">Complete Your Transfer</h3>
                                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Coordinate the formal transfer process through your respective HR departments. We provide the connection; your HR handles the official paperwork.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Eligibility Section -->
            <section class="py-16 px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-3xl p-8 lg:p-12 border border-blue-100 dark:border-blue-900/30">
                        <div class="text-center mb-10">
                            <span class="inline-block px-4 py-1.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-semibold mb-4">
                                Eligibility
                            </span>
                            <h2 class="text-3xl font-serif font-bold text-zinc-900 dark:text-white mb-4">
                                Who Can Use This Portal?
                            </h2>
                            <p class="text-zinc-600 dark:text-zinc-400 max-w-xl mx-auto">
                                The portal is available to all eligible Namibian government employees who meet the following criteria.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white dark:bg-zinc-900/50 rounded-2xl p-6 text-center border border-blue-100 dark:border-blue-900/30">
                                <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="font-bold text-zinc-900 dark:text-white mb-2">Completed Probation</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">Employees who have successfully completed their probationary period</p>
                            </div>

                            <div class="bg-white dark:bg-zinc-900/50 rounded-2xl p-6 text-center border border-blue-100 dark:border-blue-900/30">
                                <div class="w-14 h-14 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <h3 class="font-bold text-zinc-900 dark:text-white mb-2">Good Standing</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">Employees in good standing with their ministry</p>
                            </div>

                            <div class="bg-white dark:bg-zinc-900/50 rounded-2xl p-6 text-center border border-blue-100 dark:border-blue-900/30">
                                <div class="w-14 h-14 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <h3 class="font-bold text-zinc-900 dark:text-white mb-2">Government Email</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">Holders of a valid government email address</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Privacy Section -->
            <section class="py-16 px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-start">
                        <div class="lg:col-span-3">
                            <span class="inline-block px-4 py-1.5 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-sm font-semibold mb-6">
                                Privacy & Security
                            </span>
                            <h2 class="text-3xl font-serif font-bold text-zinc-900 dark:text-white mb-6">
                                Your Data is Protected
                            </h2>
                            <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-6 leading-relaxed">
                                We take your privacy seriously. Your contact details (phone numbers and email) are only revealed to other employees after a mutual connection is established.
                            </p>
                            <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Your personal information is stored securely and is never shared with unauthorized third parties. You remain in control of who can see your information at all times.
                            </p>
                        </div>

                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800">
                                <h3 class="font-bold text-zinc-900 dark:text-white mb-3">Technical Support</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3">For technical issues with the platform</p>
                                <a href="mailto:transfers@eightyseventen.com" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    transfers@eightyseventen.com
                                </a>
                            </div>

                            <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800">
                                <h3 class="font-bold text-zinc-900 dark:text-white mb-3">HR Inquiries</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400">For policy and procedural questions, please contact your ministry's HR department directly.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-16 px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="relative bg-gradient-to-br from-zinc-900 via-zinc-900 to-zinc-800 dark:from-zinc-800 dark:via-zinc-900 dark:to-zinc-950 rounded-3xl p-8 lg:p-12 overflow-hidden">
                        <!-- Background Glow -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

                        <div class="relative text-center">
                            <h2 class="text-3xl lg:text-4xl font-serif font-bold text-white mb-4">
                                Ready to Get Started?
                            </h2>
                            <p class="text-lg text-zinc-400 mb-8 max-w-xl mx-auto">
                                Create your profile today and start exploring transfer opportunities across Namibia.
                            </p>
                            @guest
                                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-zinc-900 rounded-full hover:bg-zinc-100 transition-all duration-300 text-lg font-bold">
                                    Create Your Profile
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-zinc-900 rounded-full hover:bg-zinc-100 transition-all duration-300 text-lg font-bold">
                                    Go to Dashboard
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </section>
    </main>
</div>
