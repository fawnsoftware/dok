/**
 * Registers the dialogs store, which tracks which dialogs are open
 * (in the order they were opened), closes the topmost one on Escape,
 * and locks page scroll while any dialog is open. Shared by every
 * fixed, full width, dialog style element (drawers, modals, etc).
 *
 * @param {import('alpinejs').Alpine} Alpine
 */
export default function dialogs(Alpine){
    Alpine.store('dialogs', {
        opened: [],
        unlockScroll: null,
        closeFns: {},

        /**
         * Listens for Escape globally, once, so that with several
         * dialogs open a single press only closes the topmost one.
         */
        init() {
            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    this.closeTop();
                }
            });
        },

        /**
         * Marks a dialog as open, locking scroll if it's the first one.
         * `close` is remembered so `closeTop()` can call it directly,
         * without needing to know what event (if any) a given dialog
         * type listens for to close itself.
         *
         * @param {string} name
         * @param {Function} close
         */
        add(name, close) {
            if (this.opened.length === 0) {
                this.unlockScroll = this.lockScroll();
            }

            this.opened.push(name);
            this.closeFns[name] = close;
        },

        /**
         * Marks a dialog as closed, restoring scroll once none are left.
         *
         * @param {string} name
         */
        remove(name) {
            this.opened = this.opened.filter(dialog => dialog !== name);
            delete this.closeFns[name];

            if (this.opened.length === 0 && this.unlockScroll) {
                this.unlockScroll();
                this.unlockScroll = null;
            }
        },

        /**
         * Checks if the dialog has dialogs opened after it.
         *
         * @param {string} name
         * @returns {bool}
         */
        hasDialogsOpenedAfterIt(name) {
            const index = this.opened.findIndex(dialog => dialog === name);

            if (index === -1) return false;

            return index < this.opened.length - 1;
        },

        /**
         * Closes the most recently opened dialog, if any are open.
         */
        closeTop() {
            const name = this.opened.at(-1);

            this.closeFns[name]?.();
        },

        /**
         * Locks page scrolling and compensates for the vanished
         * scrollbar with padding, so page content doesn't shift.
         *
         * This mirrors @alpinejs/focus's own `noscroll` behaviour, but
         * is applied once for the whole dialog stack rather than once
         * per dialog. Calling it per-dialog (via `x-trap...noscroll`)
         * measures the scrollbar again on every open/close - by the
         * second dialog it's already hidden by the first, so it
         * measures 0px and wipes out the real padding.
         */
        lockScroll() {
            const { style } = document.documentElement;
            const overflow = style.overflow;
            const paddingRight = style.paddingRight;

            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            const scrollbarGutter = getComputedStyle(document.documentElement).scrollbarGutter;

            style.overflow = 'hidden';

            if (! scrollbarGutter || scrollbarGutter === 'auto') {
                style.paddingRight = `${scrollbarWidth}px`;
            }

            return () => {
                style.overflow = overflow;
                style.paddingRight = paddingRight;
            };
        },
    });
}
