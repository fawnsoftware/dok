Before installing, ensure that you are using a clean install of Statamic. You can learn [how to set this up locally](https://statamic.dev/installing/local). __Skip the step where Statamic asks if you want to install a starterkit.__ Gaia is packaged as an addon, so we'll install it later.

It's also __highly recommended that you use version control__ (git) before installing Gaia so you can track your changes and roll back if something goes wrong.

## 1. Installing Gaia {#installing-gaia}
You can install Gaia with Composer:
```bash
composer require arrowtide/gaia
```

This will also install the following dependencies:
- [Livewire](https://statamic.com/addons/jonassiewertsen/livewire)
- [Rad Pack Shopify](https://statamic.com/addons/rad-pack/shopify)
- [Tailwind Merge](https://statamic.com/addons/marcorieser/tailwind-merge-statamic)

## 2. Running the Install Command {#running-the-install-command}
To streamline setup, use Gaia's built-in install command. This will automatically transfer all relevant template files to the correct directories:
```bash
php artisan gaia:install
```

## 3. Set up search {#set-up-search}
To get live search up and running we first need to head over to the `config/statamic/search.php` and add the following `indexes` configuration:
```php
    'indexes' => [
        'live-search' => [
            'driver' => 'local',
            'searchables' => ['collection:shop', 'collection:products'],
            'fields' => ['title', 'url', 'collections'],
        ],
    ],
```
You can read more about Statamic search indexes [here](https://statamic.dev/search#indexes).

## 4. Start coding
Your setup is complete! You’re now ready to begin crafting your storefront.
Happy building!
