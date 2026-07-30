---
id: 172960a5-4fc7-48a1-955b-0d1d7fecb9a2
blueprint: dok_4x
title: Preload
use_synced_content: false
---
# Preload

:::lead
Why elements can transition their default browser styles on a hard refresh, and how Dok stops it.
:::

---

[TOC]

**This mainly occurs in macOS & iOS Firefox, and usually only on hard reloads** -  an element with a transition can appear with the browser's default styling for a moment, then animate into the styles you gave it.

This happens when the page paints before your stylesheet has finished applying. Your stylesheet then lands and does two things at the same time: it changes the element's styles, and it tells that element to animate style changes.

Faster sites hit this more often, not less. The sooner the page paints, the more likely it happens before your stylesheet is ready.


## What Dok does
With that said - we need a way to defer transitions until after our stylesheet has rendered, so the browser doesn't transition from the default styles. We do this by adding a `preload` class.

Every layout starts with a `preload` class on `<body>`:

```html
<body class="preload">
```

`resources/css/base/preload.css` uses this class to turn transitions off:
{title="resources/css/base/preload.css"}
```css
.preload,
.preload * {
  transition: none;
}
```

A deferred script then removes the class on the next frame, so transitions work normally from then on:

```antlers
    {{ partial:helpers/preload }}
</body>

    <!-- Renders to: -->

    ...

    <script type="module">
        requestAnimationFrame(() => {
            document.body.classList.remove('preload');
        });
    </script>
</body>
```
