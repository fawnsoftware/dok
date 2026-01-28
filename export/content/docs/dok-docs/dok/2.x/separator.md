The seperator tag is a simple tag that renders a visual horizontal separator.

- [Basic usage](#basic-usage)
- [Custom margin](#custom-margin)
- [Separator text](#separator-text)

## Basic usage {#basic-usage}

```twig
{{ partial:components/seperator margin="lg" }}
```

## Custom margin {#custom-margin}
To customize the margin, you can pass a custom tailwind margin value to the `margin` parameter.

```twig
{{ partial:components/seperator margin="my-32" }}
```

## Separator text {#separator-text}
To add a separator text, you can pass a custom text to the `separator` parameter.

```twig
{{ partial:components/seperator margin="lg" separator="or" }}
```
