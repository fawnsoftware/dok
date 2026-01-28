The product detail page is the primary page for a product in your store. It's where customers can view, configure, and purchase your products.

- [Variant Picker](#variant-picker)
- [Filtering](#filtering)

## Variant Picker {#variant-picker}
The variant picker is the component(s) that allows you to select a variant for a product.

The default type is a `button`. You can change this by changing your config `gaia.variant_picker.default`. You can have `button` or `select`.

```diff
'variant_picker' => [
-    'default' => 'button',
+    'default' => 'select',
],
```

If you want to change the type for a single option, you can do so by adding a `custom_pickers` array to your config.

```
'variant_picker' => [
    'custom_pickers' => [
        [
            'option' => 'T-Shirt Color',
            'type' => 'color',
            'metafields' => ['color'],
        ],
    ],
]
```

1) The `option` name should match what is in your product data in Statamic:
2) The `type` key will be the type of picker you want (`button`, `select`, `color`).
3) `metafields` is used to inject metafield data into your templates and lets you display any custom information you want. Any key that is available in your variant data, you can use here. In our case, we've made a metafield on the product called `color`.


### Making your own {#making-your-own-picker-type}
If you'd like to add your own type, you can do so by adding it to `resources/views/shop/product/default/options/[your-picker-type]`. Replace `[your-picker-type]` with the name of your picker type. You'll then be able to reference your picker type inside config:

```php
'variant_picker' => [
    'custom_pickers' => [
        [
            'option' => 'Pot Size',
            'type' => 'your-picker-type',
        ]
    ]
]
```


## Filtering {#filtering}
Gaia lets you filter products by **product metadata** or **price** using Livewire. To get started, the filters you want to display should be added to `config/gaia.php`.

```php
'filters' => [
    [
        // The visual name of the filter to be shown as the title above the options
        'name' => 'Plant Type',
        
        // The key of the data you want to use. For this example there will be a 
        // plant_type: Succulent inside of the product data. 
        'use' => 'plant_type',

        // The type of filter. See above for options.
        'type' => 'product_metadata',

        // The url key of the filter, this should be unique across all filters
        // and is what will display in the url when you select options. 
        'url' => 'plant_type',
    ],
],
```

### Possible types
Here's a list of types you can currently filter by:

<div class="text-sm params not-prose">

| Name | Key | Description | 
| --- | ----------- | --- |
| Product Metadata | product_metadata | Product metadata is data that is applied to the product (not variants).

</div>



### Filtering by price
To filter by price, add the following to the `filters` array. 

```php
[
    'name' => 'Price',
    'use' => 'price',
    'type' => 'price',
    'url' => 'price',
],
```
