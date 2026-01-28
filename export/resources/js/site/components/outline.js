export default function outline(Alpine){
    Alpine.data('outline', () => ({
        active: null,
        permalinks: [],

        // The offset from the top in which the
        // permalink should be triggered
        offset: 300,

        init() {
            this.initPermalinkPositionMap()

            this.setActiveLink();
        },

        initPermalinkPositionMap() {
            this.permalinks = [];

            document.querySelectorAll('.heading-permalink').forEach(permalink => {
                const rect = permalink.getBoundingClientRect();

                this.permalinks.push({
                    el: permalink,
                    top: rect.top + window.scrollY - this.offset,
                    rect: rect,
                });
            });
        },

        isLinkActive(id) {
            return this.active === id;
        },

        setActiveLink() {
            // Get all of the y pos of permalinks to compare
            const permalinkTopValues = this.permalinks.map((value) => {
                return value['top'];
            });

            // Finds the first permalink index where its Y pos is more than scrollY pos
            const closest = (scrollY) => {
                return permalinkTopValues.findIndex((el) => {
                    return el > scrollY;
                });
            };

            // Since the first found permalink has yet to hit the trigger area,
            // we get the one BEFORE it to find the real active section.
            let closestIndex = Math.max((closest(window.scrollY) - 1), 0);

            // If we can't find one, (-1 is returned), there are no
            // more permalinks with Y pos more than scrollY pos.
            // Use the last index instead
            if (closest(window.scrollY) === -1) {
                closestIndex = permalinkTopValues.length - 1
            }

            let activePermalink = this.permalinks[closestIndex].el;

            if (activePermalink.classList.contains('heading-permalink')) {
                this.active = activePermalink.getAttribute('href').replace('#', '');

                return;
            }
        },

        eventListeners: {
            ['@scroll.window.throttle.100ms']() {
                this.setActiveLink();
            },
            ['@scroll.window.debounce.100ms']() {
                this.setActiveLink();
            },
            ['@resize.window']() {
                this.initPermalinkPositionMap();
            },
        }
    }));
}
