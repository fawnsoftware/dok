A simple heading component. Keeps your headings consistent and easy to manage. Supports heading level 1-4.

- [Basic usage](#basic-usage)
- [CSS classes](#css-classes) 
- [Impersonation](#impersonation) 
- [HTML attributes](#html-attributes) 
- [Changing the default style](#styling)

## Basic Usage {#basic-usage}

```twig
{{ partial:components/h1 heading="Step-by-Step Instructions to Organize Your Home Like a Pro" }}

<!-- Supports up to a level 4 -->
{{ partial:components/h2 }}
{{ partial:components/h3 }}
{{ partial:components/h4 }}
```

## CSS classes {#css-classes}
To add classes, use the `class` parameter.

```twig
{{ partial:components/h1 class="py-2 text-center" }}
```


## Impersonation {#impersonation}
You can change the semantic meaning of your heading without changing the size itself by using the `as` parameter. 

```twig
{{ partial:components/h1 as="h2" }}
```

```html
<!-- Will produce -->
<h2 class="h1"> ... </h2>
```


## HTML attributes {#html-attributes}
You can add attributes to the heading HTML element by prefixing the attribute with `heading` and using it as a parameter in your partial.

```twig
{{ partial:components/h1 heading:data-heading="h1" }}

{{ partial:components/h2 heading:x-text="heading_text" }}
```

```html
<!-- Will produce -->
<h1 data-heading="h1"> ... </h1>
```

## Changing the default style {#styling}
If you want to permanently change the style of your headings, head over to `resources/css/headings.css`.
