/**
 * Checks whether a given web storage type is usable.
 *
 * @param {string} type The storage type, eg. 'localStorage'
 * @returns {boolean} Whether the storage can be written to
 */
export function storageAvailable(type) {
    let storage;
    try {
        storage = window[type];
        const x = "__storage_test__";
        storage.setItem(x, x);
        storage.removeItem(x);
        return true;
    } catch (e) {
        return (
            e instanceof DOMException &&
            e.name === "QuotaExceededError" &&
            // acknowledge QuotaExceededError only if there's something already stored
            storage &&
            storage.length !== 0
        );
    }
}

/**
 * Checks whether local storage is usable.
 *
 * @returns {boolean}
 */
export const localStorageAvailable = () => storageAvailable('localStorage');

/**
 * Checks whether session storage is usable.
 *
 * @returns {boolean}
 */
export const sessionStorageAvailable = () => storageAvailable('sessionStorage');
