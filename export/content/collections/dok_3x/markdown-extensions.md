---
id: 751ec6c1-6374-494a-9121-1c4876575704
blueprint: dok_3x
title: 'Markdown Extensions'
use_synced_content: false
updated_by: cbf6fa94-2658-4dec-9152-30c80d3c652c
updated_at: 1768176152
---

# Markdown Extensions
Dok comes with loads of useful extensions to make your markdown dynamic.

[TOC]

## Code Group
The Code Group extension wraps fenced code blocks and gives them extra abilities like titles, collapsing, and tabbed content. Just like what you see in this documentation.

### Tabs
To create tabbed content, nest multiple fenced code blocks inside a `:::codegroup`.

````markdown
:::codegroup
```css
header {
    margin: 20px;
    background: blue;
}
```

```css
header {
    margin: 20px;
    background: red;
}
```
:::
````


### Title
You can change the title/tab label by using the `title="value"` attribute. The default label will be the language you specify.

`````markdown
{title="site.css"}
```css
.pop {
    color: red;
}
```
`````


### Collapsing
Create expandable code by using the `collapse="true"` attribute.

````markdown
{collapse="true"}
```css
header {
    margin: 20px;
    background:blue;
}
```
````

### Auto Grouping
This extension **automatically** finds fenced code blocks and tells them to behave like code groups. This means no extra markup is required if you just want to display the code language and copy code button.

You can turn this off in the config:
{title="config/statamic/markdown.php"}
```php
'code_group' => [
    'auto_group' => false
],
```

If you want to disable just for a single code fence, add the `codegroup="false"` attribute:

{codegroup="false"}
````markdown
{codegroup="false"}
```css
.pop {
    color: red;
}
```
````


### Examples

Tabs:

:::codegroup

{title="index.js"}
```js
function calculateSum(a, b) {
    let sum = a + b;
    console.log(`The sum of ${a} and ${b} is ${sum}`);
    return sum;
}
```

{title="header.css"}
```css
header {
    margin: 20px;
    background:blue;
}
```

:::

Tabs + collapse:


:::codegroup
{title="space.js" collapse="false"}
```js
async function getSpaceData() {
    try {
        const res = await fetch('/api/space');
        const data = await res.json();
        console.log(data);
    } catch (err) {
        console.error(err);
    }
}
```

{title="result" collapse="true"}
```js
{
    "planets": [
        "Mercury",
        "Venus",
        "Earth",
        "Mars",
        "Jupiter",
        "Saturn",
        "Uranus",
        "Neptune"
    ],
    "galaxies": [
        "Milky Way",
        "Andromeda",
        "Triangulum",
        "Whirlpool",
        "Sombrero"
    ]
}

```
:::


---

## Table Wrap
This neat little extension wraps your table blocks in a couple of HTML elements, giving a little more control over the styles. Dok already adds some useful styles, and enables overflowing for tables.

```html
<div class="markdown-table-wrapper">
    <div class="markdown-table-wrapper-inner">
        <table>
            ...
        </table>
    </div>
</div>
```

It is enabled by default.

---

## Heading Permalinks
Dok comes with the [Heading Permalink](https://commonmark.thephpleague.com/2.6/extensions/heading-permalinks/) extension pre-installed.

:::caution
Other extensions and features rely on this extension. Changing its config or disabling the extension can cause unintended consequences.
:::

---

## Table of Contents
Dok comes with the [Table of Contents](https://commonmark.thephpleague.com/2.6/extensions/table-of-contents/) extension pre-installed.

Write `[TOC]` to display the table of contents.

```
# Shopping list

[TOC]

## Fruits
- Banana
- Kiwi
- Apple
```

This is initiated inside of `AppServiceProvider.php` and its config is managed at `config/statamic/markdown.php`.
