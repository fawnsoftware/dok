@if (isset($slot))
    <div class="prose grid grid-cols-1 gap-5 md:grid-cols-2">
        {{ $slot }}
    </div>
@endif
