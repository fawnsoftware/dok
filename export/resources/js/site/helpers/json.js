/**
 * Reads and parses JSON from an inline script element.
 *
 * @param {string} id The element containing the JSON.
 * @param {string} [key] The JSON key to get. You can go deeper in the heirarchy by using not notation.
 *
 * @returns {*} The parsed JSON, or the value at the given key.
 */
export function inline(id, key) {
    const el = document.getElementById(id);
    let data;

    if (!el) {
        console.warn(`$json.inline: no element found with id "${id}"`);
        return;
    }

    try {
        data = JSON.parse(el.textContent);
    } catch (e) {
        console.warn(`$json.inline: failed to parse JSON for "${id}"`, e);
        return;
    }

    if (!key) return data;

    return key.split('.').reduce((acc, part) => {
        if (acc === undefined || acc === null) {
            console.warn(`$json.inline: key "${part}" not found`);
            return undefined;
        }
        return acc[part];
    }, data);
};
