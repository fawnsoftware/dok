<button
    role="menuitemradio"
    class="text-base-content flex items-center py-1.5 rounded-[calc(var(--radius-control)-var(--spacing))] px-4 cursor-pointer font-medium hover:bg-base-content/10 focus:bg-base-content/10 text-xs group w-full"
    {{ $attributes->except(['class']) }}
    :aria-checked="checked == $el.value"
    @click="checked = $el.value; $el.closest(`[role='radiogroup']`)._x_model.set($el.value)"
>
    <span>{{ $slot }}</span>

    <div class="size-5 ml-auto">
        <s:svg src="icon/check" class="size-full hidden fill-current group-aria-checked:block" aria-hidden="true" />
    </div>
</button>
