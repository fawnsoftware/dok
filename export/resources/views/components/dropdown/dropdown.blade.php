@props([
    'name' => null,
    'anchor' => 'bottom-center',
    'container_class' => null,
])
<span
    x-data="dropdown('{{ $name }}', '{{ $anchor }}')"
    class="{{ $container_class }} contents"
>
    {{ $slot }}
</span>
