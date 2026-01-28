export default function dropdown(Alpine){
    Alpine.data('dropdown', (uid, anchor) => ({
        open: false,
        init() {
            document.addEventListener('alpine:initialized', () => {
                this.$refs.panel.querySelectorAll('a, button, input').forEach(el => {
                    el.setAttribute('tabindex', '-1');
                    el.tabIndex = '-1';
                });
            });
        },
        toggle(open) {
            this.open = open || ! this.open;
        },
        close() {
            this.open = false;
        },
        dropdownClose() {
            this.open = false;
        },
        container: {
            ['@keydown.tab']($event) {
                this.dropdownClose();
            },
        },
        button: {
            [':aria-haspopup']() {
                return this.open;
            },
            [':aria-expanded']() {
                return this.open;
            },
            ['@click']() {
                this.toggle();
            },
            ['@keydown.down.prevent']() {
                if (!this.open) {
                    this.toggle();
                }

                this.$nextTick(() => {
                    if (this.open) {
                        let firstFocusableEl = this.$focus.within(this.$root.querySelector('[x-ref="panel"]')).getFirst();
                        this.$focus.focus(firstFocusableEl);

                        return;
                    }
                });
            },
            [':data-active']() {
                return this.open;
            },
            'type': 'button',
            'aria-haspopup': 'menu',
            'id': `dropdown-button-${ uid }`,
            'aria-controls': `dropdown-panel-${ uid }`,
        },
        dropdown: {
            ['x-show']() {
                return this.open;
            },
            ['@click.away']() {
                this.open = false;

                this.$nextTick(() =>{
                    this.$refs.button.focus();
                });
            },
            ['@keydown.down.prevent']() {
                this.$focus.wrap().next();
            },
            ['@keydown.up.prevent']() {
                this.$focus.wrap().previous();
            },
            [`x-anchor.${anchor}`]() {
                return this.$refs.button;
            },
            'id': `dropdown-panel-${ uid }`,
            'role': 'menu',
            'tabindex': '-1',
            'aria-labelledby': `dropdown-button-${ uid }`,
            'aria-orientation': 'vertical',
            'x-transition:enter': 'transition ease-out duration-200',
            'x-transition:enter-start': 'opacity-0 scale-95 translate-y-2',
            'x-transition:enter-end': 'opacity-100 scale-100 translate-y-0',
            'x-transition:leave': 'transition ease-in duration-100',
            'x-transition:leave-start': 'opacity-100',
            'x-transition:leave-end': 'opacity-0',
        }
    }));
}
