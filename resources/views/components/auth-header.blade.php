@props([
    'title',
    'description',
])

<div class="flex w-full flex-col mb-2">
    <h2 class="text-2xl sm:text-3xl font-serif font-bold text-zinc-900 dark:text-white mb-2">
        {{ $title }}
    </h2>
    <p class="text-zinc-600 dark:text-zinc-400">
        {{ $description }}
    </p>
</div>
