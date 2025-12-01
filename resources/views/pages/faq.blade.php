<?php

use App\Models\Faq;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new
#[Layout('components.layouts.guest')]
#[Title('FAQ - Employee Transfer Portal')]
class extends Component {
    public function with(): array
    {
        return [
            'faqs' => Faq::published()->ordered()->get(),
        ];
    }
}; ?>

<div class="bg-white dark:bg-zinc-900 min-h-screen">
    <!-- Hero Header -->
    <section class="relative py-24 lg:py-32 overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-b from-purple-50/50 via-zinc-50 to-white dark:from-purple-950/20 dark:via-zinc-900 dark:to-zinc-900"></div>
            <div class="absolute top-0 left-1/3 w-96 h-96 bg-purple-400/10 dark:bg-purple-500/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/3 w-80 h-80 bg-blue-400/10 dark:bg-blue-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-4xl mx-auto px-4 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-sm font-semibold mb-6">
                Help Center
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-zinc-900 dark:text-white mb-6 leading-tight">
                Frequently Asked Questions
            </h1>
            <p class="text-xl text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                Find answers to common questions about using the Employee Transfer Portal.
            </p>
        </div>
    </section>

    <main class="pb-24 px-4">
        <div class="max-w-3xl mx-auto">
            @if($faqs->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-20 bg-zinc-50 dark:bg-zinc-800/50 rounded-3xl border border-zinc-100 dark:border-zinc-800">
                    <div class="w-20 h-20 bg-zinc-100 dark:bg-zinc-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-3">No FAQs Available Yet</h3>
                    <p class="text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto">
                        We're working on adding frequently asked questions. Check back soon for updates.
                    </p>
                </div>
            @else
                <!-- FAQ Accordion -->
                <div class="space-y-4" x-data="{ openFaq: 0 }">
                    @foreach($faqs as $index => $faq)
                        <div
                            class="group rounded-2xl border transition-all duration-300"
                            :class="openFaq === {{ $index }}
                                ? 'bg-white dark:bg-zinc-800/80 border-blue-200 dark:border-blue-900/50 shadow-lg shadow-blue-500/5'
                                : 'bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-800 hover:border-zinc-300 dark:hover:border-zinc-700 hover:shadow-md'"
                        >
                            <button
                                @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                                class="w-full px-6 lg:px-8 py-6 text-left flex items-center justify-between gap-4"
                            >
                                <span
                                    class="text-lg font-semibold transition-colors"
                                    :class="openFaq === {{ $index }}
                                        ? 'text-blue-600 dark:text-blue-400'
                                        : 'text-zinc-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400'"
                                >
                                    {{ $faq->question }}
                                </span>
                                <span class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300"
                                        :class="openFaq === {{ $index }}
                                            ? 'bg-blue-600 dark:bg-blue-500 rotate-180'
                                            : 'bg-zinc-100 dark:bg-zinc-800 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30'"
                                    >
                                        <svg
                                            class="w-5 h-5 transition-colors"
                                            :class="openFaq === {{ $index }}
                                                ? 'text-white'
                                                : 'text-zinc-500 group-hover:text-blue-600 dark:group-hover:text-blue-400'"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </span>
                            </button>
                            <div
                                x-show="openFaq === {{ $index }}"
                                x-collapse
                                x-cloak
                            >
                                <div class="px-6 lg:px-8 pb-6">
                                    <div class="pt-2 border-t border-zinc-100 dark:border-zinc-700/50">
                                        <p class="pt-4 text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                            {{ $faq->answer }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Quick Links Section -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('about') }}" class="group bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800 hover:border-blue-200 dark:hover:border-blue-900/50 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-zinc-900 dark:text-white mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">About the Portal</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Learn more about our mission and how the transfer process works.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('register') }}" class="group bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl p-6 border border-zinc-100 dark:border-zinc-800 hover:border-indigo-200 dark:hover:border-indigo-900/50 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-zinc-900 dark:text-white mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Get Started</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Create your account and start exploring transfer opportunities.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Contact Section -->
            <div class="mt-16">
                <div class="relative bg-gradient-to-br from-zinc-900 via-zinc-900 to-zinc-800 dark:from-zinc-800 dark:via-zinc-900 dark:to-zinc-950 rounded-3xl p-8 lg:p-12 overflow-hidden">
                    <!-- Background Glow -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl"></div>

                    <div class="relative flex flex-col lg:flex-row items-center gap-8">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/10">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 text-center lg:text-left">
                            <h2 class="text-2xl lg:text-3xl font-serif font-bold text-white mb-3">
                                Still Have Questions?
                            </h2>
                            <p class="text-zinc-400 mb-6 max-w-lg">
                                If you couldn't find the answer you were looking for, our support team is here to help you with any questions about the portal.
                            </p>
                            <a
                                href="mailto:support@transferportal.gov.na"
                                class="group inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-zinc-900 rounded-full hover:bg-zinc-100 transition-all duration-300 font-semibold"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contact Support
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
