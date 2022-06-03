<div class="relative tvstatic lg:h-96 group">
    <img id="{{ $printer->id }}-screenshot'" class="object-cover w-full h-96" src="{{ $printer->screenshot }}" />

    <button x-data @click="Livewire.emit('pip', {{ $printer->id }})" class="absolute inset-0 z-10 flex items-center justify-center w-full h-full opacity-0 group-hover:opacity-100">
        <x-heroicon-o-play class="w-48 h-48 text-blue-500" />
    </button>
</div>
