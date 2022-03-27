@if ($attributes->has('href'))
<a {{ $attributes->merge(['class' => 'btn btn-xs btn-link']) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['class' => 'btn btn-xs btn-link', 'type' => 'button']) }}>
    {{ $slot }}
</button>
@endif
