<x-filament::page>
    @livewire('teams.update-team-name-form', ['team' => $record])

    @livewire('teams.team-member-manager', ['team' => $record])

    @livewire('teams.print-types', ['team' => $record])

    @if (Gate::check('delete', $record) && ! $record->personal_team)
    <x-jet-section-border />

    <div class="mt-10 sm:mt-0">
        @livewire('teams.delete-team-form', ['team' => $record])
    </div>
    @endif
</x-filament::page>
