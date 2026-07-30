/**
 * Wrap matched portions of `text` in <mark> tags.
 * Safe to use inside of alpine expressions.
 *
 * @returns {string} An escaped HTML string.
 */
export function mark(text, query) {
    if (!text)  return '';
    if (!query) return escapeHtml(text);
    const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    return text.replace(
        new RegExp(`(${escaped})`, 'gi'),
        '<mark>$1</mark>'
    );
};


/**
 * Truncate a string
 *
 * @param {string} text The text to truncate
 * @param {number} length The length to truncate to
 * @returns {string} The truncated string
 */
export function truncate(text, length = 100) {
    if (!text) return '';
    if (text.length <= length) return text + '…';
    return text.slice(0, text.lastIndexOf(' ', length)).trimEnd() + '…';
};

/**
 * Escapes a string
 *
 * @param {string} str The string to escape
 * @returns {string} The truncated string
 */
export function escapeHtml(str) {
    return String(str)
        .replace(/&/g,  '&amp;')
        .replace(/</g,  '&lt;')
        .replace(/>/g,  '&gt;')
        .replace(/"/g,  '&quot;');
};
