import { localStorageAvailable } from "../helpers/storage";


/**
 * Registers the search component, which powers the search dialog,
 * its recent and saved results, and the index selector.
 *
 * @param {import('alpinejs').Alpine} Alpine
 */
export default function search(Alpine){
    Alpine.data('search', (config = {}) => ({
        config: Object.assign({

            /**
             * The default index to use.
             */
            startIndex: null,

            /**
             * A list of the indexes available to this component. You don't
             * need to specify all indexes everywhere. You could have a
             * different list of indexes for different areas of your site.
             */
            indexes: null,

            /**
             * The minimum character count required to trigger a search.
             */
            minChars: 3,

            /**
             * The url endpoint to use when making the request.
             */
            endpoint: '/search/json',

            /**
             * The query parameter to use when making the request.
             */
            param: 'q',

            /**
             * The maximum number of recent results to show.
             */
            maxRecentResults: 5,

            /**
             * The storage key prefies for saved and recents.
             */
            storageRecentKeyPrefix: 'dok.search.recent',
            storageSavedKeyPrefix: 'dok.search.saved',

            /**
             * Every index should come with a label. This label will
             * be shown on recent and saved results if turned on.
             */
            showIndexLabel: true,
        }, config),

        currentSearchIndexId: null,
        focusedResultIndex: 0,
        query: '',
        settledQuery: '',
        results: [],
        recentResults: [],
        savedResults: [],
        loading: false,
        error: null,
        open: false,
        abortController: null,
        // lastFocusedElement: null,

        init() {
            if (! this.config.indexes.length) {
                console.error('[search] No indexes passed to search component.');
            }

            this.currentSearchIndexId = this.config.startIndex ? this.config.startIndex : this.config.indexes[0].value;


            if (localStorageAvailable) {
                this.recentResults = JSON.parse(localStorage.getItem(this.storageRecentKey)) || [];
                this.savedResults = JSON.parse(localStorage.getItem(this.storageSavedKey)) || [];
            }

            this.$nextTick(() => {
                this.updateOptions({shouldScroll: false});
            });
        },

        async search() {
            if (this.abortController) this.abortController.abort();
            this.abortController = new AbortController();

            const q = this.query.trim();

            if (q == '' || q.length < this.config.minChars) {
                this.results = [];
                this.focusedResultIndex = 0;

                this.$nextTick(() => {
                    this.updateOptions();
                });

                return;
            }

            this.loading = true;
            this.error = null;

            try {
                const url = new URL(this.config.endpoint, window.location.origin);
                url.searchParams.set(this.config.param, q);
                url.searchParams.set('index', this.currentSearchIndexObject.value);

                const res = await fetch(url.toString(), {
                    signal:  this.abortController.signal,
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });

                if (! res.ok) throw new Error(`HTTP ${res.status}`);

                const json = await res.json();

                this.results = json.data;

            } catch ({name, message}) {
                if (message === 'AbortError') return;

                if (message === 'HTTP 429') {
                    this.error = 'Too many requests. Please try again later.';
                    return;
                }

                console.error(`[search] ${name} ${message}`);

                this.error = 'Search is unavailable. Please try again.';
            } finally {
                this.loading = false;

                this.settledQuery = q;

                this.focusedResultIndex = 0;

                this.$nextTick(() => {
                    this.updateOptions();
                });
            }
        },

        show()  {
            this.lastFocusedElement = document.activeElement;

            /**
             * iOS and maybe others only pop up the keyboard if you tap into a text box
             * right that instant. Our input is hidden and only shows once the dialog
             * is opened, so by then it's too late to ask for the keyboard.
             *
             * Instead, focus a real, unhidden text input and then shift
             * focus to the real one after.
             */
            this.$refs.focusPlaceholder.focus();

            this.open = true;
            this.focusedResultIndex = 0;
            this.updateOptions();

            this.$nextTick(() => {
                this.$refs.input.focus();
            });
        },

        /**
         * Closes the search dialog
         */
        close() {
                this.query = '';
                this.results = [];
                this.open = false;

                this.$nextTick(() => {
                    this.lastFocusedElement.focus();
                });
        },

        /**
         * Focuses the previous result, wrapping to the last one.
         */
        moveUp() {
            this.focusedResultIndex--;

            if (this.focusedResultIndex < 0) {
                this.focusedResultIndex = this.options.length - 1;
            }

            this.updateOptions();
        },

        /**
         * Focuses the next result, wrapping to the first one.
         */
        moveDown() {
            this.focusedResultIndex++;

            if (this.focusedResultIndex > this.options.length - 1) {
                this.focusedResultIndex = 0;
            }

            this.updateOptions();
        },

        /**
         * Updates all option/li elements attributes and optionally scrolls to the element.
         *
         * @param {Object} [options={}]
         * @param {boolean} [options.shouldScroll=true]
         */
        updateOptions({shouldScroll = true} = {}) {
            let options = this.options;

            if (options.length < 1) return;

            options.forEach(option => {
                option.setAttribute('aria-selected', false);
            });

            // If we're setting focusedResultIndex below 0, the input has probably lost focus.
            // So reset all elements and only proceed if an focusedResultIndex exists.
            if (this.focusedResultIndex < 0) return;

            options[this.focusedResultIndex].setAttribute('aria-selected', true);

            if (shouldScroll) options[this.focusedResultIndex].scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" });
        },

        /**
         * Moves focus to whichever result the pointer is over.
         *
         * @param {MouseEvent} event
         */
        observeMouseMove(event) {
            if (! this.open) return;

            const li = event.target.closest('li[role="option"]')

            if (! li) return;

            this.focusedResultIndex = [...this.options].findIndex(el => el.id == li.id);

            this.updateOptions({shouldScroll: false})
        },

        /**
         * Navigates to the clicked result and adds it to recents.
         */
        handleEnterKeydown() {
            const activeOption = this.options[this.focusedResultIndex];
            const id = activeOption.dataset.identifier;

            this.addRecentResult(id);

            this.navigateTo(
                this.options[this.focusedResultIndex].querySelector('a').href
            );
        },

        /**
         * Handles when a user clicks a link.
         *
         * @param {HTMLAnchorElement} anchor
         */
        handleLinkClick(anchor) {
            event.preventDefault();

            let option = anchor.closest('li[role="option"]');

            if (! option) return;

            this.focusedResultIndex = [...this.options].findIndex(el => el.id == option.id);

            this.addRecentResult(option.dataset.identifier);

            this.navigateTo(anchor.href);
        },

        /**
         * Clears the focused result when tabbing out of the results list.
         */
        handleTabKey() {
            if (! this.open) return;

            this.focusedResultIndex = -1;

            this.updateOptions();
        },

        /**
         * Navigate to a page. Does extra cleanup
         * incase its a link to the current page.
         *
         * @param {string} href
         */
        navigateTo(href) {
            this.focusedResultIndex = 0;

            this.updateOptions();

            this.close();

            this.$nextTick(() => {
                window.location.href = href;
            });
        },

        /**
         * Moves focus to the neighbouring result, used when
         * an option is removed from the list.
         *
         * @param {HTMLElement} el An element inside the option being removed
         */
        focusAfter(el) {
            const li = el.closest('li[role="option"]');

            if (! li) return;

            let index = [...this.options].findIndex((item) => item.dataset.identifier == li.dataset.identifier);

            const next = this.options[index + 1] ?? this.options[index - 1];

            if (next) {
                this.$nextTick(() => next.querySelector('a').focus());
            } else {
                this.$refs.input.focus();
            }
        },

        /**
         * Updates the recent results and saves them to local storage.
         *
         * @param {object} result
         * @returns {array} The updated
         */
        addRecentResult(id) {
            let result;

            if (! localStorageAvailable) return;

            // If it's saved already, don't add it
            if (this.savedResults.find(item => item._identifier == id)) {
                return;
            }

            result = this.recentResults.find(item => item._identifier == id);

            if (! result) result = this.results.flat().find(item => item._identifier == id);

            if (! result) return;

            result = {...result, _indexLabel: this.currentSearchIndexObject.label}

            let recents = this.recentResults;

            // Make space for the new entry if we're at the limit
            if (recents.length >= this.config.maxRecentResults) {
                recents.shift();
            }

            recents = recents.filter(item => item._identifier !== id);

            recents.unshift(result);

            localStorage.setItem(this.storageRecentKey, JSON.stringify(recents));

            this.recentResults = recents;
        },

        /**
         * Removes a result from recents
         *
         * @param {string} resultId
         */
        removeRecentResult(resultId) {
            if (! localStorageAvailable) return;

            let results = this.recentResults;

            results = results.filter(result => result._identifier !== resultId);

            localStorage.setItem(
                this.storageRecentKey,
                JSON.stringify(results)
            );

            this.recentResults = results;
        },

        /**
         * Adds an item to saved results
         *
         * @param {string} resultId
         */
        addSavedResult(resultId) {
            if (! localStorageAvailable) return;

            let recentResults = this.recentResults;
            let savedResults = this.savedResults;
            let result = recentResults.find(result => result._identifier == resultId);

            if (! result) return;

            savedResults.unshift(result);

            localStorage.setItem(this.storageSavedKey, JSON.stringify(savedResults));

            this.savedResults = savedResults;

            this.removeRecentResult(resultId);
        },

        /**
         * Removes a result from saved results.
         *
         * @param {string} resultId
         */
        removeSavedResult(resultId) {
            if (! localStorageAvailable) return;

            let results = this.savedResults;

            results = results.filter(result => result._identifier !== resultId);

            localStorage.setItem(
                this.storageSavedKey,
                JSON.stringify(results)
            );

            this.savedResults = results;
        },


        /**
         * Gets the config object for the currently selected index.
         *
         * @returns {object|undefined} The index config, if it exists
         */
        get currentSearchIndexObject() {
            const index = this.config.indexes.find(index => index.value == this.currentSearchIndexId);

            if (! index) {
                console.error(`[search] Cannot find index [${this.currentSearchIndexId}] in indexes config`);
                return;
            }

            return index;
        },

        /**
         * Gets the input placeholder
         *
         * @returns {string}
         */
        get placeholder() {
            if (this.config?.indexes.length > 1) {
                return 'Search ' + this.currentSearchIndexObject.label + '...';
            } else {
                return 'Search documentation...'
            }
        },

        /**
         * Gets the local storage key used for recent results.
         *
         * @returns {string}
         */
        get storageRecentKey() {
            return this.config.storageRecentKeyPrefix;
        },

        /**
         * Gets the local storage key used for saved results.
         *
         * @returns {string}
         */
        get storageSavedKey() {
            return this.config.storageSavedKeyPrefix;
        },

        /**
         * Generates an option ID. Option IDs contains useful info like the
         * result type and current indexes within the option list.
         *
         * @param {string} resultType
         * @param {number|string} itemIndex
         * @param {*} groupIndex
         * @returns {string} The generated ID.
         */
        generateId(resultType, itemIndex, groupIndex = null) {
            if (groupIndex == undefined || groupIndex == null) {
                return `${resultType}-item${itemIndex}`;
            }

            return `${resultType}-group${groupIndex}-item${itemIndex}`;
        },

        /**
         * Parses an option ID. Option IDs contains useful info like the
         * result type and current indexes within the option list.
         *
         * @param {string} id The option ID to parse
         * @returns {{ resultType: string, itemIndex: number, groupIndex: (number|null) }}
         */
        parseId(id) {
            const match = id.match(/^(.+?)-(?:group(\d+)-)?item(\d+)$/);
            if (!match) return null;

            const [, resultType, groupIndex, itemIndex] = match;
            return {
                resultType,
                itemIndex: Number(itemIndex),
                groupIndex: groupIndex !== undefined ? Number(groupIndex) : null,
            };
        },


        /**
         * Gets a nodelist of the options that are currently
         * in the entire search results element
         *
         * @returns {NodeList} The list of options
         */
        get options() {
            return this.$refs.output.querySelectorAll('li[role="option"]');
        },

        /**
         * Checks whether a settled search returned nothing.
         *
         * @returns {boolean}
         */
        get noResults() {
            return !this.results.length
                && !this.loading
                && this.query.trim().length >= this.config.minChars
                && this.query.trim() == this.settledQuery
                && !this.error
        },

    }));
}
