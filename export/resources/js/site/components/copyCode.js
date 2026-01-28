export default function copyCode(Alpine) {
    Alpine.data('copyCode', () => ({
        open: false,
        text: 'Copy',
        copied: 'false',

        async writeToClipboard() {
            const el = this.$el;

            try {
                let text = el.closest('.code-group').querySelector('.code-group-panel.active pre code').textContent;

                await navigator.clipboard.writeText(text.trim());

                this.copied = 'true';
                this.text = 'Copied';

            } catch (error) {
                this.copied = 'error';
                this.text = 'Error';

                el.classList.add('animate-shake');

                console.error(error.message);

            } finally {
                setTimeout(() => {
                    this.copied = 'false';
                    this.text = 'Copy';

                    el.classList.remove('animate-shake');
                }, 1250)
            }
        },

        eventListeners: {
            [`@click`](event) {
                this.writeToClipboard();
            },
        }
    }));
}
