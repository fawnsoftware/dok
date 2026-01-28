@props([
    'title' => null,
    'icon' => null,
    'href' => null,
    'cta' => null,
])

@php
    $el = $href ? 'a' : 'div';
@endphp

<{{ $el }} @if ($href) href="{{ $href }}" @endif class="flex flex-col group border border-base-content/10 bg-base-100 @if($href) cursor-pointer no-underline prose hover:border-primary @endif rounded-box transition p-6">
    @if ($icon)
        <s:svg :src="{{ $icon }}" class="fill-primary size-6 mb-3" aria-hidden="true" />
    @endif

    @if ($title)
        <h2 class="font-bold text-base-content text-base not-prose mb-1">{{ $title }}</h2>
    @endif

    @if (isset($slot))
        <div class="text-base-content/75 prose">
            {{ $slot }}
        </div>
    @endif

    @if (isset($cta) || isset($ctaText))
        <div class="mt-auto not-prose">
            <div class="flex items-center mt-5 gap-2 text-xs group-hover:text-primary text-base-content/75">
                {{ $cta ?? $ctaText }} <s:svg src="icon/caret-right" class="fill-current size-3">
            </div>
        </div>
    @endif
</{{ $el }}>
