export default function dropdown(Alpine){
    Alpine.data('dropdown', (uid, anchor) => ({
        open: false,
        availableWidth: 0,

        init() {
            document.addEventListener('alpine:initialized', () => {
                this.$refs.panel.querySelectorAll('a, button, input').forEach(el => {
                    el.setAttribute('tabindex', '-1');
                    el.tabIndex = '-1';
                });

                const button = this.$root.querySelector('button');

                if (! button) console.warn(`[dropdown.js] Cannot find element [button] inside of [${uid}] dropdown component`);

                button.setAttribute('x-ref', 'button');
                button.setAttribute('x-bind', 'button');

                Alpine.initTree(this.$root);


                this.$nextTick(() => {
                    // ResizeObserver reports the button's size
                    // as soon as it starts observing
                    new ResizeObserver(() => {
                        this.availableWidth = this.$refs.button.offsetWidth;
                    }).observe(this.$refs.button);
                })
            });
        },

        show() {
            this.focusNext = this.$focus.within(document.body).getNext();
            this.focusPrevious = this.$focus.within(document.body).getPrevious();

            this.open = true;
        },

        close() {
            this.open = false;
        },

        button: {
            ['@keydown.tab']($event) {
                this.close();
            },
            [':aria-haspopup']() {
                return this.open;
            },
            [':aria-expanded']() {
                return this.open;
            },
            ['@click']() {
                if (this.open) {
                    this.close()
                } else {
                    this.show();
                }
            },
            ['@keydown.down.prevent']() {
                if (! this.open) {
                    this.show();
                }

                this.$nextTick(() => {
                    let firstFocusableEl = this.$focus.within(this.$refs.panel).getFirst();

                    this.$focus.focus(firstFocusableEl);
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
            ['@keydown.tab.prevent.stop']($event) {
                if (this.open) {

                    if ($event.shiftKey) {
                        this.$focus.focus(this.focusPrevious);
                    } else {
                        this.$focus.focus(this.focusNext);
                    }

                    this.close();
                }
            },
            ['x-show']() {
                return this.open;
            },
            ['@click.away']() {
                this.close();

                this.$nextTick(() =>{
                    this.$refs.button.focus();
                });
            },
            ['@keydown.down.prevent']() {
                this.$focus.within(this.$refs.panel).wrap().next();
            },
            ['@keydown.up.prevent']() {
                 this.$focus.within(this.$refs.panel).wrap().previous();
            },
            [`x-anchor.${anchor}`]() {
                return this.$root.querySelector('button');
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
