<div class="code-group" x-data="{ tab: 0, expanded: [] }">
    <div class="code-group-header">
        <div class="code-group-tab-buttons">
            @foreach ($buttons as $index => $button)
                @if ($single)
                    <span class="code-group-tab-button">
                        {{ $button['title'] }}
                    </span>
                @else
                    <button
                        type="button"
                        class="@if ($index == 0)active @endif code-group-tab-button"
                        @click="tab = {{ $button['index'] }}"
                        :class="{'active': tab == {{ $button['index'] }}}"
                    >
                        {{ $button['title'] }}
                    </button>
                @endif
            @endforeach
        </div>

        <div class="code-group-copy-button">
            <button
                x-data="copyCode()"
                x-bind="eventListeners"
                x-bind:data-copied="copied"
                class="group px-1 py-1 rounded-lg items-center justify-center cursor-pointer transition duration-100 font-bold gap-1 hidden md:flex"
            >
                <s:svg src="icon/clipboard" class="size-4 fill-current block group-data-[copied=true]:hidden group-data-[copied=error]:hidden" aria-hidden="true" />
                <s:svg src="icon/check" class="size-4 fill-primary group-data-[copied=true]:block hidden" aria-hidden="true" />
                <s:svg src="icon/x" class="size-4 fill-error group-data-[copied=error]:block hidden" aria-hidden="true" />
                <span x-text="text" class="group-data-[copied=true]:text-primary group-data-[copied=error]:text-error">Copy</span>
            </button>
        </div>
    </div>

    <div class="code-group-panels">
        @foreach ($panels as $index => $panel)
            <div
                class="@if ($index == 0)active @endif code-group-panel"
                x-show="tab === {{ $panel['index'] }}"
                x-bind:class="{ 'active': tab === {{ $panel['index'] }} }"
                @if ($index > 0)
                style="display: none"
                @endif
            >
                <div
                    class="code-group-panel-inner"
                    @if ($panel['collapse']) x-show="expanded[{{ $panel['index'] }}] === true" x-collapse.min.150px @endif
                >
                    {{ $panel['html'] }}
                </div>

                @if ($panel['collapse'])
                    <div class="code-group-footer">
                        <button
                            type="button"
                            class="code-group-expand"
                            @click="expanded[{{ $panel['index'] }}] = ! expanded[{{ $panel['index'] }}]"
                        >
                            <s:svg src="icon/dots-three" />

                            <span x-show="!expanded[{{ $panel['index'] }}]">{{ $panel['expandText'] }}</span>
                            <span x-show="expanded[{{ $panel['index'] }}]" x-cloak>{{ $panel['collapseText'] }}</span>
                        </button>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
