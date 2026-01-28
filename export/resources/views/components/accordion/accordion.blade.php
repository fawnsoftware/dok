@props([
    'title' => 'Click for more details',
])

<details class="accordion prose">
    <summary class="accordion-summary">
        <s:svg src="icon/caret-right" class="accordion-icon" />
        {{ $title }}
    </summary>
    <div class="accordion-content prose">
        @if ($slot)
            {{ $slot }}
        @endif
    </div>
</details>
