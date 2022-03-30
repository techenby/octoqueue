<div class="fixed top-0 right-0 z-50 pt-6 pr-6 pointer-events-none">
    @foreach ($notifications as $notification)
    <x-bit.notification :notification="$notification" :key="$loop->index" />
    @endforeach
</div>
