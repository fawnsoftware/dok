@props([
    'name' => '',
    'position' => 'left',
    'height' => null,
])

@php
    $position_class = [
        'left' => 'top-0 left-0',
        'right' => 'top-0 right-0',
    ][$position];

    $transition_hidden = [
        'left' => 'opacity-0 -translate-x-5',
        'right' => 'opacity-0 translate-x-5',
    ][$position];

    $transition_visible = [
        'left' => 'opacity-100 translate-x-0',
        'right' => 'opacity-100 translate-x-0',
    ][$position];
@endphp

<div
    x-data="drawer('{{ $name }}')"
    x-bind="eventListeners"
>
    <div
        class="fixed flex flex-col @if($height == 'full')min-h-dvh @endif {{ $position_class }} max-h-dvh w-dvw max-w-lg p-4 sm:pl-10 sm:p-10 z-50 translate-3d transition duration-200 pointer-events-none scrollbar-default"
        x-transition:enter-start="{{ $transition_hidden }}"
        x-transition:enter-end="{{ $transition_visible }}"
        x-transition:leave-start="{{ $transition_visible }}"
        x-transition:leave-end="{{ $transition_hidden }}"
        x-show="open"
        x-cloak
        role="dialog"
        aria-modal="true"
        x-trap.inert.noscroll="open"
    >
        <div class="relative flex flex-col h-full overflow-auto grow bg-base-200 shadow-lg rounded-lg transition duration-1000 border border-base-content/10 pointer-events-auto">

            <button @click="open = ! open" class="btn btn-square btn-outline absolute top-5 right-5">
                <s:svg src="icon/x" class="size-6" aria-hidden="true" />
                <span class="sr-only">Close"></span>
            </button>

            {{ $slot }}
        </div>
    </div>
    <div
        class="overlay transition duration-300"
        x-show="open"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        x-cloak
    ></div>
</div>
