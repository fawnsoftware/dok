---
id: cddae26f-63bb-4872-8fff-37788dc9866b
blueprint: dok_3x
title: Theme
updated_by: cbf6fa94-2658-4dec-9152-30c80d3c652c
updated_at: 1765379489
---
# Theme

:::lead
Dok includes a fun and flexible theme support system, allowing you to easily implement your branding.
:::

[TOC]

## Overview

Dok makes it easy to style your documentation with your brand's colors through a flexible theme system. You can use one of the bundled themes out of the box, tweak it to match your preferences, or build something completely new from scratch - the choice is yours.

:::codegroup
{title="resources/css/themes/forest.css"}
```css
[data-theme="coffee"] {
    --color-base-100: oklch(24% 0.023 329.708);
    --color-base-200: oklch(21% 0.021 329.708);
    --color-base-300: oklch(16% 0.019 329.708);
    --color-base-content: oklch(83.302% 0.003 326.261);
    --color-primary: oklch(71.996% 0.123 62.756);
    --color-primary-content: oklch(14.399% 0.024 62.756);

    --field-color: var(--color-base-content);
    --field-background: --alpha(var(--color-base-content) / 15%);
    --field-shadow: none;
    --field-border-color: --alpha(var(--color-base-content) / 20%);

    --radius-box: 1rem;
    --radius-control: 0.65rem;
    --radius-notch: 0.3rem;
}

```
:::

## Method
You may be used to seeing most sites with a simple `light`, `dark` and `system` themes.

Dok supports this simple method and also supports named themes like `forest`, `amethyst`, allowing you to create more options for your end users if that's your thing.

:::codegroup
{title="named"}
```js
const Theme = {
    themes: [
        { name: 'Forest',       id: 'forest'    },
        { name: 'Black',        id: 'black'     },
        { name: 'Coffee',       id: 'coffee'    },
        { name: 'Amethyst',     id: 'amethyst'  },
        { name: 'Papyrus',      id: 'papyrus'   },
        { name: 'Moonlight',    id: 'moonlight' },
        { name: 'System',       id: 'system'    },
    ]
}
```
{title="simple"}
```js
const Theme = {
    themes: [
        { name: 'Light',    id: 'light'     },
        { name: 'Dark',     id: 'dark'      },
        { name: 'System',   id: 'system'    },
    ]
}
```
:::

::::important
`id` should resolve to the name you give it in your css file.

`light`, `dark`, `system` are the exceptions to this rule. Components will intercept these and use them to decide which theme to choose if no theme has been selected.

This is why it's important to map your light/dark themes to your named themes.

:::codegroup
{title="views/helpers/_theme.antlers.html"}
```js
const Theme = {
    defaultTheme: {
        light: 'papyrus',
        dark: 'forest',
    },
}
```
:::


::::

## Changing the default theme
You can change the default theme (the theme which loads when you first visit) by updating the following:

:::codegroup
{title="views/docs/layout.antlers.html"}
```diff
- <html lang="{{ site:short_locale }}" data-theme="forest">
+ <html lang="{{ site:short_locale }}" data-theme="black">
```
:::

## Adding a new theme

1) **Add the new theme** to your `resources/css/themes/` folder.
    {title="resources/css/themes"}
    ```files
    resources/css/themes
        blurple.css
    ```
    :::codegroup
    {title="resources/css/themes/blurple.css"}
    ```diff
    [data-theme="blurple"] {
        ...
    }
    ```
    :::

2) **Import** the file. If your theme is primarily dark, you will need to add it to the variant. Dok still uses some `dark:` variants across the kit to switch between light and dark logos, among other things.
    :::codegroup
    {title="resources/css/themes/site.css"}
    ```diff
    @import './themes/papyrus.css';
    @import './themes/moonlight.css';
    @import './themes/coffee.css';
    + @import './themes/blurple.css';

    @variant dark (
        &:where(
        ...
        [data-theme="moonlight"], [data-theme="moonlight"] *,
    +     [data-theme="blurple"], [data-theme="blurple"] *,
        )
    );
    ```
    :::

3) **Edit the layout** if you want to make it your default theme:
    :::codegroup
    {title="views/docs/layout.antlers.html"}
    ```diff
    - <html lang="{{ site:short_locale }}" data-theme="forest">
    + <html lang="{{ site:short_locale }}" data-theme="blurple">
    ```
    :::

4) **Edit `Site`** to add your theme:
    :::codegroup
    {title="views/helpers/_theme.antlers.html"}
    ```diff
    const Theme = {
        themes: [
    -       { name: 'Forest',       id: 'forest'    },
    +       { name: 'Blurple',      id: 'blurple'   },
            { name: 'Black',        id: 'black'     },
            { name: 'Coffee',       id: 'coffee'    },
            { name: 'Amethyst',     id: 'amethyst'  },
            { name: 'Papyrus',      id: 'papyrus'   },
            { name: 'Moonlight',    id: 'moonlight' },
        ]
    };
    ```
    :::

Contratulations! You have now successfully added a new theme.

## Removing a theme

If the theme you want to remove is the **default** theme, make sure to change it in your layout:

:::codegroup
{title="views/docs/layout.antlers.html"}
```diff
- <html lang="{{ site:short_locale }}" data-theme="forest">
+ <html lang="{{ site:short_locale }}" data-theme="black">
```
:::

Then you can remove it from everywhere else:

:::codegroup
{title="views/helpers/_theme.antlers.html"}
```diff
const Theme = {
    themes: [
-        { name: 'Forest',       id: 'forest'    },
        { name: 'Black',        id: 'black'     },
        { name: 'Coffee',       id: 'coffee'    },
        { name: 'Amethyst',     id: 'amethyst'  },
        { name: 'Papyrus',      id: 'papyrus'   },
        { name: 'Moonlight',    id: 'moonlight' },
    ]
};
```
:::

:::codegroup
{title="resources/css/site.css"}
```diff
- @import './themes/forest.css';
```
:::





## Accessibility

### `prefers-contrast: more`
Dok fully supports the `prefers-contrast: more` media query.

We overwrite the base tailwind variant with a custom version, giving you the ability to toggle more contrast _without_ having to turn on the setting on your system. A better developer experience, and more options for end users!

:::codegroup
{title="resources/css/themes/"}
```css
@custom-variant contrast-more {
  &:where([data-contrast="more"], [data-contrast="more"] *) {
    @slot;
 }

  @media (prefers-contrast: more) {
    @slot;
  }
}
```
:::

You can edit your theme file to give more contrast across your theme colors.

:::codegroup
{title="resources/css/themes/forest.css"}
```css
[data-theme="forest"] {
    --color-primary: #5DE794;
    --color-primary-content: #212A22;

    @variant contrast-more {
        --color-primary: #000;
        --color-primary-content: #FFF;
    }
}
```
:::

You may also use the variant as a utility class, to give more contrast in specific regions of your website:

:::codegroup
```html
<p class="text-base-content/70 contrast-more:text-base-content">
```
:::

### `prefers-reduced-motion: reduce`
This theme also supports reduced motion. Nothing special here - it just uses the default [Tailwind utility](https://tailwindcss.com/docs/hover-focus-and-other-states#prefers-reduced-motion).

## Extra

### views/helpers/_theme

This file configures and applys the theme settings. Dok imports `helpers/_theme` into both layouts, but you may want to have a single theme for the rest of your website.

If so, remove it from your base layout:

:::codegroup
{title="views/layout.antlers.html"}
```diff
 <head>
-    {{ partial:helpers/theme }}
 </head>
```
:::

Make sure you change the default theme to the one you want:

:::codegroup
{title="views/layout.antlers.html"}
```html
<html data-theme="forest">
```
:::
