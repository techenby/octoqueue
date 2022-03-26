<x-app-layout>
    <x-slot name="header">{{ __('Team Settings') }}</x-slot>

    @livewire('teams.update-team-name-form', ['team' => $team])

    @livewire('teams.team-member-manager', ['team' => $team])

    @if (Gate::check('delete', $team) && ! $team->personal_team)
        <x-jet-section-border />

        <div class="mt-10 sm:mt-0">
            @livewire('teams.delete-team-form', ['team' => $team])
        </div>
    @endif

    @livewire('teams.job-type-manager', ['team' => $team])

    @livewire('teams.preferences', ['team' => $team])
</x-app-layout>
