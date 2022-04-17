<div {{ $attributes->merge(['class' => 'flex flex-col']) }} >
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black dark:ring-gray-700 ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-850">
                        {{ $head }}
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        {{ $body }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
