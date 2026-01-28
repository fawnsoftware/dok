@props([
    'name' =>  null,
    'type' => 'note',
    'size' => 'base',
    'title' => null,
    'style' => '',
    'icon' => 'hint/hint-note',
    'content' => null,
])

@php
    // Markdown component passes $name in, so use this as the type
    $type = $name ?? $type;

    $icon = [
        'note' => 'hint/hint-note',
        'important' => 'hint/hint-important',
        'caution' => 'hint/hint-caution',
        'danger' => 'hint/hint-danger',
        'tip' => 'hint/hint-tip',
        'warning' => 'hint/hint-warning',
    ][$type] ?? 'hint/hint-note';

    $size = [
        'sm' => 'hint-sm',
        'base' => 'hint-base',
    ][$size] ?? 'hint-base';

    $style = [
        'soft' => 'hint-soft',
        'base' => 'hint-base',
    ][$style] ?? 'hint-base';
@endphp

<div class="prose hint {{ $size }} hint-{{ $type }} {{  $style }}">
    <div class="hint-icon not-prose">
        @if ($icon)
            <s:svg :src="{{ $icon }}" />
        @endif
    </div>
    <div class="hint-content-container">
        @if ($title)
            <strong class="hint-title">
                {{ $title }}
            </strong>
        @endif

        <div class="hint-content prose">
            @if ($slot)
                {{ $slot }}
            @endif
        </div>
    </div>
</div>
