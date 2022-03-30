@props(['notification', 'key'])

<div class="mb-2 bg-white rounded-lg shadow-lg pointer-events-auto w-96" x-data="{ dismissed: false }"
    x-init="dismissed = false; setTimeout(function(){ dismissed = true; window.livewire.emit('dismiss', {{ $key }}) }, 3000)"
    x-show="!dismissed">
    <div class="overflow-hidden rounded-lg shadow-xs">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    @if ($notification['type'] === 'success')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @elseif ($notification['type'] === 'error')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    @endif
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium leading-5 text-gray-800">
                        {{ $notification['message'] ?? 'no message' }}
                    </p>
                </div>
                <div class="flex flex-shrink-0 ml-4">
                    <button wire:click="dismiss({{ $key }})"
                        class="inline-flex text-gray-400 transition duration-150 ease-in-out focus:outline-none focus:text-gray-500">
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
