@props([
    'name' => '',
    'position' => 'left',
    'height' => null,
])

@php
    $classes = [
        'radius' => [
            'left' => 'sm:rounded-r-box',
            'right' => 'sm:rounded-l-box'
        ],
        'border' => [
            'left' => 'sm:border-r-default border-base-content/10',
            'right' => 'sm:border-l-default border-base-content/10',
        ],
        'position' => [
            'left' => 'left-0',
            'right' => 'right-0'
        ],
        'animation' => [
            'left' => [
                'enter-start' => 'opacity-0 !-translate-x-10',
                'enter-end' => 'opacity-100 !translate-x-0',
                'leave-start' => 'opacity-100 !translate-x-0',
                'leave-end' => 'opacity-0 !-translate-x-10',
            ],
            'right' => [
                'enter-start' => 'opacity-0 !translate-x-10',
                'enter-end' => 'opacity-100 !translate-x-0',
                'leave-start' => 'opacity-100 !translate-x-0',
                'leave-end' => 'opacity-0 !translate-x-10',
            ],
        ]
    ];
@endphp

<div
    x-data="drawer('{{ $name }}')"
    x-bind="eventListeners"
    class="contents"
>
    <template x-teleport="body">
        <div class="fixed inset-0 overflow-hidden bg-transparent w-dvw h-dvh min-w-dvw min-h-dvh z-50" x-cloak x-show="open">
            <div
                class="absolute w-full h-full sm:max-w-[500px] text-base-content focus-visible:outline-none bg-base-200 z-50 transform-gpu transition-discrete translate-x-0 transition-all duration-400 ease-in-out {{ $classes['radius'][$position] }} {{ $classes['border'][$position] }} {{ $classes['position'][$position] }}"
                x-transition:enter-start="{{ $classes['animation'][$position]['enter-start'] }}"
                x-transition:enter-end="{{ $classes['animation'][$position]['enter-end'] }}"
                x-transition:leave-start="{{ $classes['animation'][$position]['leave-start'] }}"
                x-transition:leave-end="{{ $classes['animation'][$position]['leave-end'] }}"
                x-show="open"
                x-cloak
                role="dialog"
                aria-modal="true"
                x-trap.inert="shouldTrap"
            >
                <div tabindex="0"></div>
                <div class="pt-12 h-full overflow-auto">

                    <button @click="close()" class="btn btn-square size-15 absolute z-50 top-3 right-3">
                        <s:svg src="icon/x" class="size-6" aria-hidden="true" />
                        <span class="sr-only">Close</span>
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
                @click="close()"
                x-cloak
            ></div>
        </div>
    </template>
</div>
