
- [Deferring Alpine](#deferring-alpine)
- [Stylesheet optimisation](#stylesheet-optimisation)
- [Deferring layout](#deferring-layout)
- [Caching](#caching)

### Deferring Alpine {#deferring-alpine}

AlpineJS is different to most frontend frameworks in how it scans the DOM to initialise elements. This can make initial load times slow, by increasing INP and therefore your Google Pagespeed score. We've tried to mitigate this by building a simple `x-defer` directive.

Let's say you have an Alpine component that doesn't need to be ready directly on load. By adding `x-defer` to that root element, Alpine will now instead initialse that DOM tree on a brand new task. This leaves more room for other important browser tasks to be completed first. 


```html
<div x-data="posts()" x-defer>
    ...
</div>
```

<p class="callout warning">
    You cannot use x-defer within a livewire component. 
</p>

You can view the full docs on github. It comes with a few more options to optimise further, but the below is the easiest and the best bang for your buck. 

## Stylesheet optimisation {#stylesheet-optimisation}

We recommend trying to keep your css file small. You can do this by:
- Trying to reuse your theme classes and utilities as much as possible, and use arbitrary values as infrequently as possible.
- Avoiding complicated selectors. Selectors that match children, siblings and parents are more computationally expensive.

That isn't to say you should't do these these, but if you can achieve the same result with simpler classes you should. 


## Deferring layout {#deferring-layout}

`content-visibility` is a really powerful CSS attribute available in all major browsers, essentially providing you the ability to defer the expensive layout and painting calculations done by the browser on an element until it's visible in the viewport.

You can learn about this on [Web.dev](https://web.dev/articles/content-visibility), or if you prefer video form a video is available on [Youtube](https://www.youtube.com/watch?v=FFA-v-CIxJQ).


## Caching
Gaia supports both half and full measure caching. To learn more about caching strategies, read about it in the [Statamic docs](https://statamic.dev/static-caching#caching-strategies). 

