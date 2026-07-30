---
id: 82dfc30f-8916-40c3-8841-61b4937ba56f
blueprint: dok_4x
title: 'Markdown - Getting Started'
use_synced_content: false
---
# Markdown: Getting Started

:::lead
Learn how syntax highlighting, nested components, and markdown security work in Dok.
:::

[TOC]

## Syntax Highlighting

Syntax highlighting is handled by [Torchlight Engine](https://github.com/torchlight-api/engine), if selected during install.

Change the theme or update options like line numbers in the `config/dok.php` file.

[View a list of available options.](https://github.com/torchlight-api/engine?tab=readme-ov-file#options)

[View a list of available themes.](https://github.com/torchlight-api/engine?tab=readme-ov-file#available-themes)

## Nesting Components
Both components and extensions support nesting, and follow the same syntax rules. There are two different syntaxes that Dok supports.

The first option: increase the fence characters on the outer blocks:
````markdown
::::cardgroup
:::card
My card content
:::
::::
````

The above method can get messy once you nest blocks within each other. For this reason you can define an ending tag like below:

```markdown
:::cardgroup
:::card
My card content
:::/card
:::/cardgroup
```

## Security

Dok follows the security principles established by [League/CommonMark](https://commonmark.thephpleague.com/2.x/security/). As Dok components are built as CommonMark extensions, League/CommonMark and its config handle security.

In short, if you allow markdown input from potentially **untrusted users**, use the CommonMark config settings to escape it. Dok ships with the `html_input` option set to `escape`.

---

Components, and their bound blade files, are **assumed safe** because users cannot edit them directly. Consequently, CommonMark's `html_input` has no effect on their rendering.

However, user-generated input can reach your bound component two ways: attributes or slots. Both are run through CommonMark's `HtmlFilter`, returning filtered (or unfiltered) input depending on your setting. If you don't accept untrusted input, you don't need to worry about this.

:::note
By default, Blade's `{{ }}` echoes are automatically escaped via PHP's htmlspecialchars function, to prevent XSS attacks.

However, data sent to your components via CommonMark **skips this step** for both attributes and slots, since it's an instance of `Htmlable`. Dok does this so all input is handled consistently by your `html_input` CommonMark setting.
:::


