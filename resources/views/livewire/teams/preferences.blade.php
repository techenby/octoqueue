<div>
    <x-jet-section-border />

    <div class="mt-10 sm:mt-0">
        <x-jet-form-section submit="save">
            <x-slot name="title">
                {{ __('Preferences') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Configure how the team\'s data is displayed.') }}
            </x-slot>

            <x-slot name="form">
                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="currency" value="{{ __('Currency Symbol') }}" />

                    <x-form.input id="currency" type="text" class="block w-full mt-1" placeholder="$" wire:model.defer="team.settings.currency" />

                    <x-jet-input-error for="currency" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="unit" value="{{ __('Weight Unit') }}" />

                    <x-form.input id="unit" type="text" class="block w-full mt-1" placeholder="g" wire:model.defer="team.settings.unit" />

                    <x-jet-input-error for="unit" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="welcome" value="{{ __('Show Welcome Help on Dashboard') }}" />

                    <div class="flex mt-1 space-x-4">
                        <x-form.radio id="welcome-yes" name="welcome" wire:model.defer="team.settings.welcome" value="1" label="Yes" />
                        <x-form.radio id="welcome-no" name="welcome" wire:model.defer="team.settings.welcome" value="0" label="No" />
                    </div>

                    <x-jet-input-error for="welcome" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>

                <x-jet-button>
                    {{ __('Save') }}
                </x-jet-button>
            </x-slot>
        </x-jet-form-section>
    </div>
</div>
