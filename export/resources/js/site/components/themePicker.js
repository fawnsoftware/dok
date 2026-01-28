export default function themePicker(Alpine){
    Alpine.data('themePicker', () => ({
        themes: Theme.themes || null,
        currentTheme: 'system',
        moreContrast: false,

        handleMoreContrastChange($el) {
            const isChecked = $el.checked ? 'more' : 'unset';

            document.documentElement.setAttribute(Theme.contrastAttrName, isChecked);

            localStorage.setItem(Theme.contrastAttrName, isChecked);
        },

        init() {
            this.initTheme();
            this.initContrast();
        },

        initTheme() {
            const saved = localStorage.getItem(Theme.themeAttrName);

            this.currentTheme = saved;
        },

        initContrast() {
            const contrastValue = localStorage.getItem(Theme.contrastAttrName);

            if (contrastValue === 'more') {
                this.$refs.moreContrastCheckbox.checked = true;
            }
        },

        enableTheme(theme) {
            this.currentTheme = theme || this.currentTheme;

            if (this.currentTheme === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                const theme = prefersDark ? Theme.defaultTheme.dark : Theme.defaultTheme.light;

                this.currentTheme = theme;
            }

            if (this.currentTheme === 'light') {
                this.currentTheme = Theme.defaultTheme.light;
            }

            if (this.currentTheme === 'dark') {
                this.currentTheme = Theme.defaultTheme.dark;
            }

            localStorage.setItem(Theme.themeAttrName, this.currentTheme);

            document.documentElement.setAttribute(Theme.themeAttrName, this.currentTheme);
        },
    }));
}
