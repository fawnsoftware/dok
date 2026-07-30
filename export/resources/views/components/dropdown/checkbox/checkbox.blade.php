@props([

])

<button
    x-data="{checked: false}"
    role="menuitemcheckbox"
    class="text-base-content flex items-center py-1.5 rounded-[calc(var(--radius-control)-var(--spacing))] px-4 cursor-pointer font-medium hover:bg-base-content/10 text-xs group w-full"
    {{ $attributes->except(['class', 'x-data', 'role', 'checked', 'x-modelable', '@click']) }}
    :aria-checked="checked"
    :checked="checked"
    @click="checked = !checked"
    x-modelable="checked"
>
    <span>{{ $slot }}</span>

    <div class="size-5 ml-auto">
        <s:svg src="icon/check" class="size-full hidden fill-current group-aria-checked:block" aria-hidden="true" />
    </div>
</button>
