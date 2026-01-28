---
id: 5ba459a9-c59b-444c-a1fe-101ffab90d66
blueprint: dok_3x
title: 'Markdown Components'
use_synced_content: false
updated_by: cbf6fa94-2658-4dec-9152-30c80d3c652c
updated_at: 1768929256
parent: 6b840926-cefd-4f71-b327-1ab3a0c2d5ca
---
# Components

[TOC]

## All Components
Components are very similar to some extensions that already come bundled, but key difference is they use a single generic extension to _bind_ them to Blade components. We call this Component Binding.

To dive deeper check out the configuration at `config/statamic/markdown.php`, and the respective Blade view for each component to see what `props` are available to each component.

### Hint
You can use hints - also known as callouts, admonitions or tips - in your markdown. Choose from `note`, `tip`, `important`, `caution`, `warning`.

```markdown
:::note
Your note content
:::
```

You can add a title to a hint by adding the `title` attribute.

```markdown
:::note title="My note title"
Note content
:::
```

:::note
This is a note hint
:::


### Accordion
You can use a collapsible accordion in your markdown.


```markdown
:::accordion title="Your accordion title"
Your accordion content
:::
```

:::accordion title="Your accordion title"
Your accordion content
:::


### Cards


```markdown
:::cardgroup

:::card title="Card title"
The quick brown fox jumped over the lazy dog.
:::/card

:::card title="Card title"
The quick brown fox jumped over the lazy dog.
:::/card

:::/cardgroup
```

:::cardgroup

:::card title="Card title"
The quick brown fox jumped over the lazy dog.
:::/card

:::card title="Card title"
The quick brown fox jumped over the lazy dog.
:::/card

:::/cardgroup

## Creating Components
The **Component Binding** feature allows you to bind markdown container blocks to Blade components, just by changing a configuration file.

This enables you to create and render elements such as cards, banners, and graphs without needing to write a new CommonMark extension. It provides a consistent pattern for authors to use, whilst giving you full control of the logic and markup.

Lets create a simple banner component:

{title="config/markdown.php"}
```php
'components' => [
    'banner' => [
        'template' => 'components.banner.banner',
        'slots' => [],
    ],
]
```

When the component has been mapped you can create your blade file:

{title="views/components/banner/banner.blade.php"}
```blade
@props([
    'title' => 'My banner block!',
])

<div class="banner">
    <h2>{{ $title }}</h2>
    @if ($slot)
        {{ $slot }}
    @endif;
</div>
```

Now you can use that component inside of your markdown!

{codegroup="false"}
```code
:::banner heading="Heading text"
The quick red fox jumped over the lazy dog.
:::/banner
```

## Attributes
You can pass attributes to components.

:::note
`name` is a reserved attribute name. The name of the component (as defined in your config) is passed to your bound component, exposed through the `$name` variable. This might be useful if you want to use the same view for multiple components.
:::

You should use this 99% of the time.
{codegroup="false"}
```markdown
:::banner size="large"
```

If you want to pass in a string with quotes, you can use the single quote syntax instead. This is assumed HTMl so quotes are escaped.
{codegroup="false"}
```
:::banner heading='The dog said "Woof"'
```

Or pass in a single attribute:
{codegroup="false"}
```
:::banner outlined
```

## Slots
You can make your components even more flexible by using slots. You will need to add the slot name to the configuration, otherwise it will be ignored.

```php
'banner' => [
    'template' => 'components.banner.index',
    'slots' => ['footer'],
],
```

You can now use the slot inside of your banner block:

```markdown
:::banner

:::slot.footer
This is my footer slot! You can write markdown
here just like you would anywhere else.
:::/slot

:::/banner
```

The `footer` slot will now return the rendered markdown:
```blade
@if ($footer)
    {{ $footer }}
@endif
```

:::important
It's generally recommended to not share the same variable name for slots and components. For instance having a`prop` and `slot` both named `footer`.
:::

:::note
Slots don't actually get sent to your bound component **as** a slot, so the methods you'd typically see via `$slot->hasActualContent` are not available.
:::

## Security
We have a whole section on security. You can read about [component security](/dok/3.x/markdown-getting-started#security) there.
