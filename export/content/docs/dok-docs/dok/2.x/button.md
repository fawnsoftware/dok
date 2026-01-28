A button tag is used to render a button.

- [Basic usage](#basic-usage)
- [Variants](#variants)
- [Sizes](#sizes)
- [Responsive](#responsive)
- [Square and round buttons](#square-and-round)
- [Icons](#icons)
    - [Position](#icon-position)
    - [Classes](#icon-classes)
- [Loading state](#loading-state)
- [As a link](#as-a-link)
- [HTML attribute slots](#html-attribute-slots)
- [Slots](#slots)

## Basic usage {#basic-usage}

```twig
{{ partial:components/button text="Button" }}
```

## Variants {#variants}
You can customise the button by passing a variant to the `variant` parameter.

```twig
{{ partial:components/button variant="primary" }}
{{ partial:components/button variant="secondary" }}
{{ partial:components/button variant="ghost" }}
{{ partial:components/button variant="danger" }}
```

## Sizes {#sizes}
There are three different sizes for buttons. You can customise the sizes of the button by using the `class` parameter, and adding the classname of your desired size. 

```twig
{{ partial:components/button class="btn-sm" }}
{{ partial:components/button class="btn-base" }}
{{ partial:components/button class="btn-lg" }}
```

## Responsive {#responsive}
If you need different buttons and styles for different screen sizes, you can simply use css inside the `class` parameter. 

```twig
{{ partial:components/button class="btn-sm md:btn-base lg:btn-lg" }}
```

## Square and round buttons {#square-and-round}
You can create square and round buttons by using the `btn-square` and `btn-round` classes. Since these will be icon only buttons, the text is hidden and replaced by screen-reader only text.

```twig
{{ partial:components/button class="btn-square" }}
{{ partial:components/button class="btn-round" }}
```

## Icons {#icons}
Icons are added to the button by using the `icon` parameter.

```twig
{{ partial:components/button icon="account" }}
```

<p class="callout note">
    This uses the [svg tag](https://statamic.dev/tags/svg) under the hood, so the icon should be in one of the directories the svg tag cascades through. Gaia puts all icons in the `resources/svg` directory, so it's recommended to put your icons in there too.
</p>

### Icon position {#icon-position}
The icon position can be set to `start` or `end` by using the `icon_position` parameter.

```twig
{{ partial:components/button icon="account" icon_position="start" }}
{{ partial:components/button icon="account" icon_position="end" }}
```

### Icon classes {#icon-classes}
The icon classes can be customised by using the `icon_class` parameter. This will override the default classes.

```twig
{{ partial:components/button icon="account" icon_class="w-4 h-4" }}
```


## Loading state {#loading-state}
The button component comes with a loading state that can be triggered by an Alpine reative variable. You can use the `loading` parameter to pass in the name of that variable. 

```twig
<div x-data="getPosts()">
    {{ partial:components/button text="Get Posts" loading="getPostsLoading" button:@click="getPosts()" }}
</div>

<script>
    function getPosts() {
        return {
            getPostsLoading: false,

            getPosts() {
                this.getPostsLoading = true;
            }   
        }
    }
</script>
```

If you are using livewire, you can use the `wire:loading.attr` directive to toggle the loading state instead.

```twig
{{ partial:components/button button:wire:loading.attr="data-loading" }}
```

## As a link {#as-a-link}
You can change the element for the button by using the `as` parameter. This let's you easily create links as buttons.

```twig
{{ partial:components/button as="a" button:href="gaiakit.com" }}
```


## HTML attribute slots {#html-attribute-slots}
You can use the `button:` prefix to pass in any HTML attributes to the button tag.

```twig
{{ partial:components/button 
    text="Button" 
    button:@click="getPosts()" 
    button:type="submit"
    button::disabled="getPosts"
}}
```

## Slots {#slots}

### `text`
The `text` slot can be used to override the default text of the button. This may be useful if you need to conditionally set the text of the button with Alpine.

```twig
{{ partial:components/button text="Button" }}
    {{ slot:text }}
        <span>My custom text</span>
    {{ /slot }}
{{ /partial:components/button }}
```

### `attrs`
The `attrs` slot can be used to add any additional attributes to the button that you can't already do with the [Button Attributes Slot](#html-attribute-slots). This may be useful if you need to conditionally set the attributes of the button.

```twig
{{ partial:components/button }}
    {{ slot:attrs }}
        {{ if something}}
            data-attr="true"
        {{ else }}
            data-attr="false"
        {{ /if }}
    {{ /slot:attrs }}
{{ /partial:components/button }}
```
