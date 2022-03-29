<div class="{{ isset($level) ? 'ml-4' : '' }}">
    @foreach ($files as $file)
        @includeWhen($file['type'] === 'folder', 'livewire.bit.printer-files.folder', ['folder' => $file])
        @includeWhen($file['type'] === 'machinecode', 'livewire.bit.printer-files.file', ['file' => $file])
    @endforeach
</div>
