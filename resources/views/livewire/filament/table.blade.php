<x-slot name="header">
    <x-layout.header :title="$title" :breadcrumbs="$breadcrumbs">
        @isset($link)
        <x-jet-button href="{{ route($link['route']) }}">{{ $link['label'] }}</x-jet-button>
        @endif
    </x-layout.header>
</x-slot>

<div>
    {{ $this->table }}
</div>
