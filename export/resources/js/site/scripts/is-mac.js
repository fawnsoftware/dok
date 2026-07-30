/**
 * Checks whether the visitor is on a Mac
 */
(() => {
    const platform = window.navigator?.userAgentData?.platform || window.navigator.platform;
    const macosPlatforms = ['macOS', 'Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'];

    if (macosPlatforms.indexOf(platform) !== -1) {
        document.documentElement.classList.add('os-macos');
    }
})();
