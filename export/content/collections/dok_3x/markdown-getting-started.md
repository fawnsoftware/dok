---
id: f215cbcb-e412-4c2a-afe6-f484e7c16653
blueprint: dok_3x
title: 'Markdown - Getting Started'
use_synced_content: false
updated_by: cbf6fa94-2658-4dec-9152-30c80d3c652c
updated_at: 1768951040
---
# Markdown: Getting Started

[TOC]

## Syntax Highlighting

Syntax highlighting is done by [Torchlight Engine](https://github.com/torchlight-api/engine), if you select it when installing the starter kit.

Changing the theme, or updating options such as enabling line numbers can be done in the `config/dok.php` file.

[View a list of available options.](https://github.com/torchlight-api/engine?tab=readme-ov-file#options)

[View a list of available themes.](https://github.com/torchlight-api/engine?tab=readme-ov-file#available-themes)

## Nesting Components
Both components and extensions support nesting, and follow the same syntax rules. There are two different syntaxes that Dok supports.

The first option is to increase the number of fence characters on the outer blocks:
````markdown
::::cardgroup
:::card
My card content
:::
::::
````

You might have noticed the above method above could get quite messy once you start nesting blocks within each other. For this reason you can define an ending tag like below:

```markdown
:::cardgroup
:::card
My card content
:::/card
:::/cardgroup
```

## Security

Dok follows the security principles established by [League/CommonMark](https://commonmark.thephpleague.com/2.x/security/). As Dok components are built as CommonMark extensions, security is primarily handled by League/CommonMark and its configuration options.

In short, if you are allowing markdown input from potentially **untrusted users**, it's highly recommended to use the settings in the CommonMark config to escape the input. Dok ships with the `html_input` option set to `escape`.

---

Components, and respectively their bound blade files, are **assumed safe** because users cannot edit them directly. Consequently, CommonMarks `html_input` has no effect on their rendering.

However, there are two ways to get user-generated input through to your bound component: through attributes or slots. These are both ran through CommonMarks `HtmlFilter` and depending on your setting, return the filtered (or non-filtered) input. If you aren't accepting untrusted user input you don't really need to worry about this.

:::note
By default, Blade's `{{ }}` echo statements are automatically sent through PHP's htmlspecialchars function to prevent XSS attacks.

However the data sent to your components via CommonMark **skip this step** for both attributes and slots, due to being an instance of `Htmlable`. Dok does this to ensure a cohesive environment where all input is handled by your `html_input` Commonmark setting.
:::


