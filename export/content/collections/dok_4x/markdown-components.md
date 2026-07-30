---
id: 9d087ad6-2c26-47bd-b066-32c06f11dc02
blueprint: dok_4x
title: 'Markdown Components'
use_synced_content: false
parent: 8cf1a360-dcdb-4fe3-a3e2-b6cfb4e9f0e1
---
# Components

:::lead
Get started creating your very own components - all without having to create a new CommonMark extension!
:::

[TOC]

## Creating Components
The **Component Binding** feature lets you bind markdown container blocks to Blade components, just by editing a config file.

This lets you create and render elements like cards, banners, and graphs, without writing a new CommonMark extension. It gives authors a consistent pattern to use, while you keep full control of the logic and markup.

Let's create a simple banner component:

{title="config/markdown.php"}
```php
'components' => [
    'banner' => [
        'template' => 'components.banner.banner',
        'slots' => [],
    ],
]
```

When the component has been mapped, you can create your blade file:

{title="views/components/banner/banner.blade.php"}
```blade
@props([
    'heading' => 'My banner block!',
])

<div class="banner">
    <h2>{{ $heading }}</h2>
    @if ($slot)
        {{ $slot }}
    @endif
</div>
```

Now you can use that component in your markdown!

{codegroup="false"}
```code
:::banner heading="Heading text"
The quick red fox jumped over the lazy dog.
:::/banner
```

## Attributes
You can pass attributes to components.

:::note
`name` is a reserved attribute name. The component's name (as defined in your config) is passed to your bound component via the `$name` variable. This is useful if you want to reuse the same view for multiple components.
:::

You should use this 99% of the time.
{codegroup="false"}
```markdown
:::banner size="large"
```

To pass a string with quotes, use single quotes instead. This is assumed HTML, so quotes are escaped.
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
Slots make your components even more flexible. Add the slot name to the configuration, or it will be ignored.

```php
'banner' => [
    'template' => 'components.banner.banner',
    'slots' => ['footer'],
],
```

You can now use the slot in your banner block:

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
It's generally recommended not to share the same variable name for slots and components. For instance, avoid having a `prop` and a `slot` both named `footer`.
:::

:::note
Slots aren't actually sent to your bound component **as** a slot, so methods like `$slot->hasActualContent` aren't available.
:::

## Security
We have a whole section on security. You can read about [component security](/dok/3.x/markdown-getting-started#security) there.



## All Components
Components are similar to some bundled extensions, but the key difference is they use one generic extension to _bind_ them to Blade components. We call this Component Binding.

To dive deeper, check the configuration at `config/statamic/markdown.php`, and each component's Blade view for its available `props`.

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
