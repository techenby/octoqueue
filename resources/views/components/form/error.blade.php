@props(['error' => false])

@if ($error)
<div class="mt-1 text-sm text-red-400">{{ $error }}</div>
@endif
