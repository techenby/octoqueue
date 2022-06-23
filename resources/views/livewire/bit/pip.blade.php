<div>
    @if ($showModal)
    <div class="fixed bottom-0 right-0 z-10 h-64 mb-4 mr-4 overflow-hidden rounded-lg shadow-lg w-96 group">
        <button wire:click="resetModal" class="absolute top-0 left-0 mt-1 ml-1 opacity-0 group-hover:opacity-100">
            <x-heroicon-o-x class="w-12 h-12 p-1 text-blue-500 rounded-full drop-shadow-lg bg-white/70" />
        </button>
        <img id="{{ $printer->id }}-webcam'" src="{{ $printer->webcam }}" />
    </div>
    @endif
</div>
