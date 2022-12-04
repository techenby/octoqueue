@props(['full' => false])

<main class="py-12">
    <div class="{{ $full ? 'px-12' : 'mx-auto max-w-7xl' }} sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
</main>
