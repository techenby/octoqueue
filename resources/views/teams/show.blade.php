<x-app-layout>
    <x-layout.header :title="__('Team Settings')" />

    <x-ui.container>
        @livewire('teams.update-team-name-form', ['team' => $team])

        @livewire('teams.team-member-manager', ['team' => $team])

        @livewire('teams.print-types', ['team' => $team])

        @if (Gate::check('delete', $team) && ! $team->personal_team)
            <x-jet-section-border />

            <div class="mt-10 sm:mt-0">
                @livewire('teams.delete-team-form', ['team' => $team])
            </div>
        @endif
    </x-ui.container>
</x-app-layout>
