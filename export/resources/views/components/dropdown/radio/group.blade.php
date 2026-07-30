<div role="radiogroup" class="flex flex-col gap-1" x-data="{checked: null}" x-modelable="checked" @if ($xModel) x-model="{{ $xModel }}" @endif>
    {{ $slot }}
</div>
