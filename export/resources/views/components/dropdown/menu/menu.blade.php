<template x-teleport="body" @click.away @keydown.down.prevent @keydown.up.prevent>
    <div
        class="fixed z-50 p-1.5 mt-2 overflow-auto will-change-transform min-w-48 bg-base-100 border-default border-base-content/15 w-(--x-dropdown-menu-width) contrast-more:border-base-content/80 rounded-control shadow-lg"
        x-cloak
        x-bind="dropdown"
        x-ref="panel"
        x-bind:style="{'--x-dropdown-menu-width': `${availableWidth}px`}"
    >
        {{ $slot }}
    </div>
</template>
