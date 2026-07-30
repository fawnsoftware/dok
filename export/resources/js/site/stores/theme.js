function forceReflow() {
    getComputedStyle(document.documentElement).opacity;
}

export default function theme(Alpine){
    Alpine.store('theme', {
        themes: window.Theme?.themes || [],
        currentTheme: 'system',
        moreContrast: false,
        useViewTransitions: true,

        init() {
            if (! window.Theme) return;

            this.initTheme();
            this.initContrast();

            Alpine.watch(() => this.currentTheme, value => {
                this.enableTheme(value);
            });

            Alpine.watch(() => this.moreContrast, value => {
                value = value ? 'more' : 'unset';
                document.documentElement.setAttribute(Theme.contrastAttrName, value);
                localStorage.setItem(Theme.contrastAttrName, value);
            });

            // Follow OS or browser light/dark switches while 'system' is selected
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (this.currentTheme === 'system') {
                    this.enableTheme('system');
                }
            });
        },

        /**
         * Restores the saved theme state
         */
        initTheme() {
            const saved = localStorage.getItem(Theme.themeAttrName) || 'system';

            // A saved theme may have been removed from the manifest since
            this.currentTheme = saved === 'system' || this.themes.some(theme => theme.id === saved)
                ? saved
                : 'system';
        },

        /**
         * Restores the saved contrast state.
         */
        initContrast() {
            const contrastValue = localStorage.getItem(Theme.contrastAttrName);

            if (contrastValue === 'more') {
                this.moreContrast = true;
            }
        },

        /**
         * Applies a theme to the page and saves it. Passing 'system'
         * resolves to the default theme for the current appearance.
         *
         * @param {string} [theme] The theme ID, or 'system'
         */
        enableTheme(theme) {
            this.currentTheme = theme || this.currentTheme;

            document.documentElement.classList.add('theme-updating');

            if (document.startViewTransition && this.useViewTransitions) {
                document.startViewTransition(() => this.swapTheme())
                    .finished.finally(() => {
                        document.documentElement.classList.remove('theme-updating');
                    });
            } else {
                this.swapTheme();
                document.documentElement.classList.remove('theme-updating');
            }
        },

        swapTheme() {
            forceReflow();

            document.documentElement.setAttribute(Theme.themeAttrName, Theme.resolveId(this.currentTheme));
            document.documentElement.setAttribute(Theme.appearanceAttrName, Theme.resolveAppearance(this.currentTheme));

            localStorage.setItem(Theme.themeAttrName, this.currentTheme);

            forceReflow();
        },

        get resolvedTheme() {
            return Theme.resolveId(this.currentTheme);
        },

    });
}
