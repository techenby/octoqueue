<x-guest-layout>
    <div class="pt-4 bg-gray-100 dark:bg-gray-900">
        <div class="flex flex-col items-center min-h-screen pt-6 sm:pt-0">
            <div>
                <x-jet-authentication-card-logo />
            </div>

            <div class="w-full p-6 mt-6 overflow-hidden prose bg-white shadow-md prose-blue dark:prose-invert dark:bg-gray-800 sm:max-w-2xl sm:rounded-lg">
                {!! $copy !!}
            </div>
        </div>
    </div>
</x-guest-layout>
