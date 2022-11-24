<div>
    <x-layout.header :title="$title" :breadcrumbs="$breadcrumbs">
        @isset($link)
        <x-jet-button href="{{ route($link['route']) }}">{{ $link['label'] }}</x-jet-button>
        @endif
    </x-layout.header>

    <x-ui.container>
        <div class="{{ config('forms.dark_mode') ? 'dark' : '' }}">
            {{ $this->table }}
        </div>
    </x-ui.container>
</div>
