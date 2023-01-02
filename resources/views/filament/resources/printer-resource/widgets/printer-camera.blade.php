<x-filament::widget>
    <div class='bg-white rounded-xl shadow dark:border-gray-600 dark:bg-gray-800 overflow-hidden relative' x-data="{view: 'webcam'}">
        <img src="{{ $record->webcam }}" x-show="view === 'webcam'" alt="Webcam Stream" class="object-fill w-full h-full" />
        <img src="{{ $record->screenshot }}" x-show="view === 'screenshot'" alt="Screenshot of Webcam Stream" class="object-fill w-full h-full" />
        <div class="absolute inset-0 flex items-center justify-center w-full h-full opacity-0 group-hover:opacity-100 bg-gray-200/30">
            <div>
                <button @click="view = 'webcam'" x-show="view === 'screenshot'" class="btn btn-base btn-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                    </svg>
                    <span class="sr-only">Play</span>
                </button>
                <button @click="view = 'screenshot'" x-show="view === 'webcam'" class="btn btn-base btn-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
                    </svg>
                    <span class="sr-only">Pause</span>
                </button>
            </div>
        </div>
    </div>
</x-filament::widget>
