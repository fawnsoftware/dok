---
id: 66bb61eb-b034-449b-8a91-5b7ff6513c00
blueprint: dok_4x
title: Theme
---
# Theme

:::lead
Dok includes a fun, flexible theme system, so you can easily implement your branding.
:::

[TOC]

## Overview

Dok makes it easy to style your docs with your brand's colors, using a flexible theme system. Use a bundled theme out of the box, tweak it, or build something new from scratch - the choice is yours.

:::codegroup
{title="resources/css/themes/forest.css"}
```css
[data-theme="forest"] {
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

## Theme manifest
Dok comes with theme configuration file to manage all of your themes inside `dok-themes.yaml`. Themes specified in the `themes` array are used in the theme selector dropdown.

A simple Vite function generates a `themes.css` file with the required imports - this generated file is then imported into your `site.css` stylesheet. This means if you want to add a theme you only need to add the theme file and edit the manifest - the theme selector will automatically pick up these changes.

:::codegroup
{ collapse="true" title="dok-themes.yaml" }
```yaml
default:
  dark: forest
  light: papyrus
themes:
  - id: forest
    name: Forest
    appearance: dark
  - id: black
    name: Black
    appearance: dark
  - id: white
    name: White
    appearance: light
  - id: coffee
    name: Coffee
    appearance: dark
  - id: amethyst
    name: Amethyst
    appearance: light
  - id: papyrus
    name: Papyrus
    appearance: light
  - id: moonlight
    name: Moonlight
    appearance: dark
```
:::


###  Manifest options

| Option | Description |
|---|---|
| `default.dark` | This value should be the `id` of the theme you want to choose for your default dark theme. |
| `default.light` | This value should be the `id` of the theme you want to choose for your default light theme. |
| `themes` | An array of your themes. All themes here will automatically be imported and used in the theme selector dropdown. |
| `themes[].id` | The id of your theme. This should be the same value as your filename and the `data-theme` value in that file. |
| `themes[].name` | The human readable name of your theme. |
| `themes[].appearance` | If this theme is primarily `dark` or `light`. This changes the `data-appearance` attribute on the `html` element, which allows you to change certain aspects of your site using the `dark:` Tailwind variant. |

## Default themes
Default themes are shown when a user first uses the webpage or when `system` is selected from the theme selector. Which theme is used depends on the users system preferences.

{title="dok-themes.yaml"}
```yaml
default:
  dark: forest
  light: papyrus
```

## Scenarios
Dok's theme system is built to bend to whatever your site needs, whether that's a single fixed theme, a simple light and dark pair, or a full set for readers to choose from. Most of it comes down to your dok-themes.yaml manifest, so switching between these setups is usually just a few lines of config. Below are some common scenarios, and how to configure each one.

### Light, Dark, System
If you just want a simple light and dark theme, you can use this template as your manifest file. Make sure you have `light.css` and `dark.css` files in your themes directory.

{ title="dok-themes.yaml" }
```yaml
default:
  dark: dark
  light: light
themes:
  - id: light
    name: Light
    appearance: light
  - id: dark
    name: Dark
    appearance: dark
```

### Single theme
If you want to use a single theme for your site, you will need to do some manual edits:
1) Import the CSS theme file manually to your main stylesheet.
2) Edit `data-theme` and `data-appearance` manually in the layout.
3) remove the `helpers/_theme` partial from the layout.

### All dark or all light
You may want to display _only_ dark themes, or _only_ light themes. You should edit your manifest to force a single theme:

{ title="dok-themes.yaml" }
```yaml
default:
  dark: night
  light: night
themes:
  - id: night
    name: Night
    appearance: dark
  - id: dusk
    name: Dusk
    appearance: dark
```

Now, regardless of what `prefers-color-scheme` is set to, your `night` theme will be used. If that's your thing. If you do this you should also remove the hard-coded system option in the theme dropdown.

## Creating a theme

Create your theme file:

{title="resources/css/themes/blurple.css"}
```css
[data-theme="blurple"] {

}
```

Then add the theme to the `themes` array in `dok-themes.yaml`:

{title="dok-themes.yaml"}
```json
themes:
  - id: blurple // [tl! ++]
    name: Blurple // [tl! ++]
    appearance: light // [tl! ++]
```

## Removing a theme

Comment out or remove entirely from the `themes` array inside `dok-themes.yaml`:

{title="dok-themes.yaml"}
```json
themes:
  - id: forest // [tl! --]
    name: Forest // [tl! --]
    appearance: dark // [tl! --]
```




## Accessibility

### More Contrast
Dok fully supports the `prefers-contrast: more` media query.

We overwrite the base Tailwind variant with a custom one, letting you toggle more contrast _without_ turning on the system setting. A better developer experience, and more options for end users!

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

Edit your theme file to give more contrast to your theme colors.

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

You can also use the variant as a utility class, for more contrast in specific regions:

:::codegroup
```html
<p class="text-base-content/70 contrast-more:text-base-content">
```
:::

### Reduced Motion
This theme also supports reduced motion. Nothing special here - it just uses the default [Tailwind utility](https://tailwindcss.com/docs/hover-focus-and-other-states#prefers-reduced-motion).


## Tips

:::accordion title="Page transition when changing themes"
In v4.0 a transition was added to the page when changing themes.

If this causes conflicts with other elements on your page, disable it by removing the code snippet entirely:

```css
.theme-updating, // [tl! --]
.theme-updating * { // [tl! --]
} // [tl! --]
```
:::

