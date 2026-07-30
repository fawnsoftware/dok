---
id: b991e2c5-a059-465e-b179-5bb9bcf3f43d
blueprint: dok_4x
title: 'Markdown Extensions'
use_synced_content: false
---
# Markdown Extensions

:::lead
Dok comes with a handful of extensions to make your markdown more dynamic.
:::

[TOC]

## Code Group
The Code Group extension wraps fenced code blocks, adding titles, collapsing, and tabbed content - just like you see in this documentation.

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
    background: blue;
}
```
````

### Auto Grouping
This extension **automatically** finds fenced code blocks and turns them into code groups. This means no extra markup is needed for the language label and copy button.

You can turn this off in the config:
{title="config/statamic/markdown.php"}
```php
'code_group' => [
    'auto_group' => false
],
```

To disable it for a single code fence, add the `codegroup="false"` attribute:

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
    background: blue;
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


## Table Wrap
This neat extension wraps your table blocks in a couple of HTML elements, giving more control over styling. Dok already adds some useful styles and enables overflowing for tables.

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

## Heading Permalinks
Dok comes with the [Heading Permalink](https://commonmark.thephpleague.com/2.6/extensions/heading-permalinks/) extension pre-installed.

:::caution
Other extensions and features rely on this extension. Changing its config or disabling the extension can cause unintended consequences.
:::

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

This is registered inside `AppServiceProvider.php`, and its config is managed at `config/statamic/markdown.php`.
