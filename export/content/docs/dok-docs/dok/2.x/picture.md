The picture component lets you display a responsive image. 

- [Basic usage](#basic-usage)
- [Sizes](#sizes)
- [Glide options](#glide-options)
    - [Exclusions](#glide-exclusions)
- [Lazy loading](#lazy-loading)
- [HTML attribute slots](#html-attribute-slots)

## Basic usage {#basic-usage}

```twig
{{ partial:components/picture 
    :image="url" 
    sizes:default="w=480, h=480" 
    sizes:xs="w=600, h=600" 
    sizes:sm="w=800, h=800" 
/}}
```

## Sizes {#sizes}
Sizes are set using the `sizes:` prefix. This accepts a comma separated list of sizes, which will be used to generate the `srcset` attribute.

The size names are defined in your projects config, under `gaia.screens`. This makes it easy to remember your breakpoints and keep them consistent across your project as they're the same names used in your Tailwind config.


```twig
{{ partial:components/picture 
    sizes:default="w=480, h=480" 
    sizes:xs="w=600, h=600" 
    sizes:sm="w=800, h=800" 
/}}
```

<p class="callout warning">
    The default size is required.
</p>


You don't have to use the screens defined in your config, you can use arbitrary values too:

```twig
{{ partial:components/picture 
    sizes:default="w=480, h=480" 
    sizes:502="w=600, h=600" 
    sizes:810="w=800, h=800" 
/}}
```

Internally, the srcset is based on `min-width`, so using the example above, the sizes you define basically mean:

- `default` - Screen widths 0px and higher
- `502` - Screen widths 502px and higher
- `md` - Screen widths 800px and higher


## Passing glide options {#glide-options}
You can pass any [Glide options](https://statamic.dev/tags/glide#parameters) except for the ones listed in [exclusions](#glide-exclusions) to the picture component by prefixing the option with `glide:`.

```twig
{{ partial:components/picture glide:quality="50" glide:sharpen="10" }}
```

If you find yourself needing to define glides based on screen size, we recommend just using plain HTML with antlers instead. but this tag should fit most use cases.

### Exclusions {#glide-exclusions}
Some Glide options should not be passed to the picture tag as they are set automatically:
- `dpr`
- `width`
- `height`

`dpr` is set already on both tags, and by default the picture tag will output @1x and @2x variants for each size. If you need to change this, edit the picture tag directly. 

`width` and `height` are set automatically based on the `sizes` attribute.



## Lazy loading {#lazy-loading}
Images are lazy loaded by default. You can change this by setting the `loading` attribute to `eager`.

```twig
{{ partial:components/picture loading="eager" }}
```

Sometimes, you may want to load the image immediately (`eager`) for the first image in something like a gallery, where the image is the Largest Contentful Paint (LCP) element. Here, you can simply use antlers as normal within the `loading` attribute.

```twig
{{ partial:components/picture loading="{ index == 0 ? 'high' : 'low' }" }}
```

## HTML attribte slots {#html-attribute-slots}
The picture component has a number of HTML attribute slots that you can use to add additional attributes to the html tags. Simply prefix the attribute with one of the below options.

### `img`

```twig
{{ partial:components/picture 
    :image="url" 
    img:class="object-cover w-full h-full" 
    img:data-index="1" 
/}}
```

```html
<img class="object-cover w-full h-full" data-index="1" ... />
```


### `picture`

```twig
{{ partial:components/picture 
    :image="url" 
    picture:class="object-cover w-full h-full" 
    picture:data-index="1" 
/}}
```

```html 
<picture class="object-cover w-full h-full" data-index="1" ... />
```
