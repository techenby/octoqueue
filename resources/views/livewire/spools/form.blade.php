<x-jet-form-section submit="save">
    <x-slot name="title">Spool Configuration</x-slot>
    <x-slot name="description">Configure a spool of filament that the printers can use to print.</x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="brand" value="{{ __('Brand') }}" />
            <x-form.input id="brand" type="text" class="block w-full mt-1" wire:model="spool.brand" autofocus />
            <x-jet-input-error for="spool.brand" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-2">
            <x-jet-label for="cost" value="{{ __('Cost') }}" />
            <x-form.input id="cost" type="text" wire:model="spool.cost" class="block w-full" :leading="$teamSettings['currency'] ?? '$'" />
            <x-jet-input-error for="spool.cost" class="mt-2" />
        </div>

        <div wire:ignore class="col-span-6 sm:col-span-3 coloris-square">
            <x-jet-label for="color_hex" value="{{ __('Color Hex') }}" />
            <x-form.input id="color_hex" type="text" data-coloris class="block w-full mt-1" wire:model.lazy="spool.color_hex" />
            <x-jet-input-error for="spool.color_hex" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="color" value="{{ __('Color Name') }}" />
            <x-form.input id="color" type="text" class="block w-full mt-1" wire:model="spool.color" />
            <x-jet-input-error for="spool.color" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="material" value="{{ __('Material') }}" />
            <x-form.select id="material" type="text" class="block w-full mt-1" wire:model="spool.material" placeholder="Select material" :options="$materials" />
            <x-jet-input-error for="spool.material" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-jet-label for="diameter" value="{{ __('Diameter') }}" />
            <x-form.input id="diameter" type="text" class="block w-full mt-1" wire:model="spool.diameter" />
            <x-jet-input-error for="spool.diameter" class="mt-2" />
        </div>

        @if ($spool->id === null)
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="initial-weight" value="{{ __('Initial Weight') }}" />
            <x-form.input id="initial-weight" type="text" class="block w-full" wire:model="initialWeight" :trailing="$teamSettings['unit'] ?? 'g'" />
            <x-form.help>Weigh the spool and input the result here or use a best guess.</x-jet-help>
            <x-jet-input-error for="initialWeight" class="mt-2" />
        </div>
        @endif

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="empty-weight" value="{{ __('Empty Spool Weight') }}" />
            <x-form.input id="empty-weight" type="text" class="block w-full" wire:model="spool.empty" :trailing="$teamSettings['unit'] ?? 'g'" />
            <x-form.help>Weigh an empty spool of the same brand and input the result here or use a best guess (industry average is ~250g).</x-jet-help>
            <x-jet-input-error for="initialWeight" class="mt-2" />
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ $spool->id === null ? __('Create') : __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>


@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css"/>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>
    <script>
        Coloris({
            themeMode: 'auto',

        // The margin in pixels between the input fields and the color picker's dialog.
        margin: 6,

        // Set the preferred color string format:
        // * hex: outputs #RRGGBB or #RRGGBBAA (default).
        // * rgb: outputs rgb(R, G, B) or rgba(R, G, B, A).
        // * hsl: outputs hsl(H, S, L) or hsla(H, S, L, A).
        // * auto: guesses the format from the active input field. Defaults to hex if it fails.
        // * mixed: outputs #RRGGBB when alpha is 1; otherwise rgba(R, G, B, A).
        format: 'hex',

        // Set to true to enable format toggle buttons in the color picker dialog.
        // This will also force the format (above) to auto.
        formatToggle: false,

        // Enable or disable alpha support.
        // When disabled, it will strip the alpha value from the existing color value in all formats.
        alpha: true,

        // Set to true to hide all the color picker widgets (spectrum, hue, ...) except the swatches.
        swatchesOnly: false,

        // Focus the color value input when the color picker dialog is opened.
        focusInput: true,

        // Show an optional clear button and set its label
        clearButton: {
            show: true,
            label: 'Clear'
        },

        // An array of the desired color swatches to display. If omitted or the array is empty,
        // the color swatches will be disabled.
        swatches: [
            'red',
            'orange',
            'yellow',
            'green',
            'blue',
            'purple',
            'pink',
            'black',
            'gray',
            'silver',
            'white',
        ]
        });
    </script>
@endpush
