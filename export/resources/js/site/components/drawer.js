export default function drawer(Alpine){
    Alpine.data('drawer', (name) => ({
        open: false,
        eventListeners: {
            [`@open-${name}.window`]() {
                this.open = true;
            },
        }
    }));
}
