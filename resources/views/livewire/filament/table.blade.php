<div>
    <x-layout.header :title="$title" :breadcrumbs="$breadcrumbs">
        @isset($link)
        <x-jet-button href="{{ route($link['route']) }}">{{ $link['label'] }}</x-jet-button>
        @endif
    </x-layout.header>

    <x-ui.container>
        {{ $this->table }}
    </x-ui.container>
</div>
