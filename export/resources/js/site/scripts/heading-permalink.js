/**
 * A utility function allowing users to click the heading content to 'focus' that heading.
 * The commonmark permalink extension only adds a permalink element to the opening heading.
 */
window.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', (event) => {

        // Check if you're on the heading content element, or inside it.
        if (event.target.classList.contains('heading-content') || event.target.closest('.heading-content')) {
            const heading = event.target.closest('h1:has(.heading-permalink), h2:has(.heading-permalink), h3:has(.heading-permalink), h4:has(.heading-permalink), h5:has(.heading-permalink)')

            if (!heading) return;

            window.location.href = heading.querySelector('a.heading-permalink').href;
        }
    });
})
