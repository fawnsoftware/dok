@props([
    'heading' => null
])

<div>
    @if ($heading)
        <h2 class="block font-medium text-xs pb-3 pt-2 text-base-content/60 px-2 contrast-more:text-base-content">
            {{  $heading }}
        </h2>
    @endif

    {{ $slot }}
</div>

<x-divider class="last:hidden my-1.5 -mx-1.5" />
