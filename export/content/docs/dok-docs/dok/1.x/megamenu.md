## Introduction
Gaia comes with an accessible, robust, and extensible mega menu. We've separated the mobile and desktop versions of the menu into their own files for disambiguation (we tried to build them together, it was not fun to develop for - at all). 

We ship with the essential blocks to get you started:
- Megamenu
- Row
- Column
- Mobile Stack
- Link Group
- Link

## Adding custom blocks
If you want a purely link based navigation, the base blocks are all you will need. If you find you want to extend the menu with your own blocks, we've made this really easy. 

1) Add a new type to your navigation blueprint


```
-
    handle: type
    field:
        options:
        -
            key: megamenu
            value: Megamenu
        -
            key: column
            value: Column
        -
            key: row
            value: Row
        -
            key: mobile_stack
            value: 'Mobile Stack'
        -
            key: links
            value: 'Links Group'
        -
            key: link
            value: Link
        -
            key: custom_sale_banner
            value: Sale Banner
        type: select
        display: Type
        default: link
        localizable: false
```

<p class="callout note">
    If you plan on keeping Gaia updated with the latest changes, we recommend prefixing your blocks with `custom_` so there aren't any conflicts in the future. 
</p>

2) Make your templates:

```
resources/views/layout/navigation
│ 
│ 
└─── mobile/blocks
│   │   custom_sale_banner.antlers.html
│   |
└───desktop/blocks
|    |   custom_sale_banner.antlers.html
```

```handlebars
<!-- mobile/blocks/custom_sale_banner.antlers.html-->
<div>
    This is all you!

    If your block has children, you can import the children partial:

    {{ partial:layout/navigation/mobile/children }}
</div>
```

3) Nice! You can now select that block in your navigation and use it. 
