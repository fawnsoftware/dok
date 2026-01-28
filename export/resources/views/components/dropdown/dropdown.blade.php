@props([
    'name' => null,
    'anchor' => 'bottom-center',
    'container_class' => null,
])
<span
    x-data="dropdown('{{ $name }}', '{{ $anchor }}')"
    class="{{ $container_class }}"
    x-bind="container"
>
    <span class="w-full flex">
        {{ $button }}

        <div
            class="fixed z-50 p-1 mt-2 overflow-auto min-w-[9rem] bg-base-200 border border-base-content/15 contrast-more:border-base-content/80 rounded-box shadow-lg content-visibility-auto w-52"
            x-cloak
            x-bind="dropdown"
            x-ref="panel"
        >
            {{ $content }}
        </div>
    </span>
</span>
