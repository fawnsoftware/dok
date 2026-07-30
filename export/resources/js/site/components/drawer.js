/**
 * Registers the drawer component. Drawers are opened and closed
 * from anywhere on the page by dispatching a window event, and
 * register with the shared dialogs store so several can be
 * stacked at once (eg. scroll lock, Escape, focus trapping).
 *
 * @param {import('alpinejs').Alpine} Alpine
 */
export default function drawer(Alpine){
    Alpine.data('drawer', (name) => ({
        open: false,
        hasChildrenOpenedAfterIt: false,

        /**
         * Only the topmost open drawer should actually trap focus and
         * mark its siblings inert - otherwise two active traps fight
         * over Tab navigation when drawers are stacked.
         */
        get shouldTrap() {
            return this.open && !this.hasChildrenOpenedAfterIt;
        },

        init() {
            this.$watch('$store.dialogs.opened', () => {
                this.hasChildrenOpenedAfterIt = this.$store.dialogs.hasDialogsOpenedAfterIt(name);
            });
        },

        /**
         * Opens the drawer.
         */
        show() {
            this.open = true;

            this.$store.dialogs.add(name, () => this.close());
        },

        /**
         * Closes the drawer.
         */
        close() {
            this.open = false;

            this.$store.dialogs.remove(name);
        },

        eventListeners: {
            [`@drawer:open:${name}.window`]() {
                this.show();
            },
            [`@drawer:close:${name}.window`]() {
                this.close();
            },
        }
    }));
}
