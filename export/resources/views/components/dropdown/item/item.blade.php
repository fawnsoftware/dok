@props([
    'href' => null
])

<{{ $href ? 'a' : 'button'}}
    @if ($href) href="{{ $href }}" @endif
    class="btn rounded-[calc(var(--radius-control)-var(--spacing))] hover:bg-base-content/10 focus:bg-base-content/10 text-xs justify-between w-full"
    role="menuitem"
    {{ $attributes->except(['class', 'role', 'href']) }}
>
    {{ $slot }}
</{{ $href ? 'a' : 'button'}}>
