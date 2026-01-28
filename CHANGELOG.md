# v3.0.0
Dok has undergone a full rewire of all systems, bringing more features, better flexibility, enhanced extendibility.

## What's new

- **Complete frontend theme overhaul**
    - Theme variables are now semantic utilities. Instead of using creating variables like `--sidebar-bg-color` and `--sidebar-color`, which quickly gets out of hand, you can use `--color-base-100`, `--color-base-content`.
    - Added 6 new themes (`forest`, `black`, `white`, `coffee`, `amethyst`, `moonlight`)
    - Added `accordion`, `button`, `checkbox`, `fileinput` `heading` `input`, `label`, `radio`, `select`, `textarea`, `toggle` UI styles
    - Better support for `prefers-contrast: more`
    - Better support for `prefers-reduced-motion: reduce`
    - All phosphor icons now come bundled

- **Tags & Commands**
    - Complete rework of built-in tags, bringing a better syntax and more robust data fetching
    - You can now scaffold projects and releases via an interactive command

- **Components**
    - Components are now blade and can even be used in markdown (once bound via Component Binding)
    - Added `Card`, `Card Group`, `Accordion`, `Hint` components

- **Extensions**
    - Added `codegroup` markdown extension to transform fenced code blocks. You can now add titles, tabs, collapsible sections and a copy code button
    - Added `TabelWrap` markdown extension to wrap tables in additional HTML for styling purposes
    - Added `HeadingWrap` markdown extension to wrap inner heading content for better style and logic flexibility

- **Added outline component.**
    - Added sidebar 'outline' / 'on this page' navigation

- **And more**
    - Added Laravel Precognition. Alpine and form templates all included
    - Images are now supported as project logos
    - HTML/text is now supported as project logos

- **Statamic v6**
    - Dok has been updated to support Statamic v6. (^beta-4)

## What's changed:
- The whole heading is now clickable, if it contains a permalink
- Using JetBrains Mono for the fenced code font
- Synced content is now on the entry level, instead of the collection
- Sync utility has been revamped using v6 UI


## What's fixed:
- Components like `hint` are now indentable within lists and more
- Added missing `aria-hidden` attributes to svg icons
- When syncing content, the target folder is emptied before syncing
