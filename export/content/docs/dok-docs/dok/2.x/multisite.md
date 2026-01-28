## Updating Translations
You can find the translation files under your `resources/lang` folder. There is a single common file `default.php`, and then a file for each substantial feature. 

```md
// The default translation file.
default.php

// If you have enabled multiple wishlists, the templates will use this file
wishlist_collections.php
```

## Setting up per site currencies
When you set up multi-site, you'll probably want to display different currencies for each site you have. These currencies should match what you have set up in Shopify. Currently this is only used for the `{{ gaia:currency }}` tag, but may be expanded in the future.

Let's say you have set up two markets and changed their currency:

- United Kingdom (GBP)
- France (EUR)

Now you'll want to add a `currency` key to the `attributes` array inside `resources/sites.yaml`. 

```yaml
default:
  name: en
  locale: en_GB
  url: '{{ config:app:url }}'
  lang: en
  attributes:
    currency: GBP
fr:
  name: fr
  locale: fr
  url: '{{ config:app:url }}fr/'
  lang: fr
  attributes:
    currency: EUR
```