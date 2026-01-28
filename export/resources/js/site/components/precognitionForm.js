import "../../../../vendor/statamic/cms/resources/dist-frontend/js/helpers.js"

export default function precognitionForm(Alpine) {
    Alpine.data('precognitionForm', () => ({
        success: false,
        submitted: false,
        form: null,

        init() {
            this.form = this.$form(
                'post',
                this.$refs.form.getAttribute('action'),
                JSON.parse(this.$refs.form.getAttribute('x-data')).form,
                {
                    headers: {
                        'X-CSRF-Token': {
                            toString: () => this.$refs.form.querySelector('[name="_token"]').value,
                        }
                    }
                }
            )
        },

        async submit() {
            this.submitted = true;
            this.success = false;

            this.form.submit()
                .then(() => {
                    this.form.reset();
                    this.$refs.form.reset();
                    this.success = true;
                    this.submitted = false;

                    this.form.errors = {};
                })
                .then(() => this.scrollToTop())
                .catch(() => {
                    this.scrollToTop();
                    this.$refs?.errors?.focus();
                });
        },

        scrollToTop() {
            const rect = this.$refs.form.getBoundingClientRect();

            const yPos = (window.scrollY + rect.y) - this.scrollMargin(this.$refs.formInner);

            window.scrollTo(0, yPos);
        },

        scrollMargin(el) {
            let value = getComputedStyle(el).scrollMarginTop || null;

            if (! value || ! value.includes('px')) return 0;

            return value.replace('px', '');
        }
    }));
}
