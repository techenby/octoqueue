<form wire:submit.prevent="connect">
    <div class="space-y-2">
        <x-form.group type="select" model="form.port" label="Serial Port" :options="$options['ports']" placeholder="Auto" />
        <x-form.group type="select" model="form.baudrate" label="Baudrate" :options="$options['baudrates']" placeholder="Auto" />
        <x-form.group type="select" model="form.printerProfile" label="Printer Profile" :options="$options['printerProfiles']" />
        <x-form.checkbox wire:model="form.save" label="Save connection settings" />
        <x-form.checkbox wire:model="form.autoconnect" label="Auto-connect on server startup" />
    </div>
    <button type="submit" class="mt-4 btn btn-base btn-blue">Connect</button>
</form>
