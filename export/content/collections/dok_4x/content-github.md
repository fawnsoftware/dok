---
id: 8e792e35-8bc5-4fa8-b995-e0dc312170b6
blueprint: dok_4x
title: Github
---
# Using GitHub

:::lead
Sync from multiple repositories, nested folders, and across users/organisations. Do this through the control panel or a command.
:::

## Getting Started
Dok makes it easy to use GitHub as the source for your documentation. You can even mix content sources across collections - you're not tied to just one. This is handy when your docs live in the same repo as your product.

You can even sync content from _different_ owners and organisations, as long as your access token has the right permissions.

:::important
Assumes you have already created a GitHub personal access token. [Learn how to create a personal access token.](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens#creating-a-fine-grained-personal-access-token)
:::

To get started, add a new value to your config in `config/dok.php`. The below is an example. You may need to clear your Laravel cache after changing this.

{title="config/dok.php"}
```php
'resources' => [
  'your_project' => [

        'source' => 'github',

        // The repository to sync from.
        'repo' => 'owner/repo',

        // The branch to sync from.
        'branch' => 'main',

        // An array of folders to sync.
        // The below would only sync the docs folder.
        // Leave empty to import everything.
        'content' => ['docs'],

        // Any content that should be public and
        // imported into the public folder
        'public' => [
            'images/*' => public_path('media'),
        ],

        // The env variable for your Github personal access token.
        'token' => env('GITHUB_SYNC_TOKEN'),
  ],
],
```

You can now sync that resource. For entries using this content, toggle **Use synced content** to `true` and select your resource file from the dropdown.


## Public content
You might want to keep media, like images or videos, in the repository itself, to keep everything together.

You can make folder contents public by adding them to the `public` array key. All paths should be relative to the repository root.

{codegroup="false"}
```php
'resources' => [
  'dok' => [
    'public' => [
        'docs/media/*' => public_path('media'),
        'docs/documents' => public_path('media'),
    ],
  ]
],
```

Use a wildcard to import everything _inside_ the folder, but not the folder itself.
{codegroup="false"}
```php
'docs/media/*' => public_path('media')

// docs/media/lake.jpg -> /media/lake.jpg
```


To import the folder and contents:
{codegroup="false"}
```php
'docs/media' => public_path('media')

// docs/media/lake.jpg -> /media/images/lake.jpg
```

If you run multiple projects, categorise your media by project to avoid name conflicts:
{codegroup="false"}
```php
'docs/media/*' => public_path('media/dok')

// docs/media/lake.jpg -> '/media/dok/lake.jpg
```


You'd then reference images in your markdown by their public path:
{codegroup="false"}
```php
'docs/media/*' => public_path('media')
// ![Image of a lake](/media/lake.jpg)

'docs/media' => public_path('media/dok')
// ![Image of a lake](/media/docs/media/lake.jpg)
```

## Syncing
To sync via the control panel, head to the **Utilities page**, under **Remote Sync**.

Run the following command to sync a resource using an interactive UI:

```shell
php artisan dok:sync:github
```

Skip the interactive stage by providing a `--resource` flag. Replace `name` with the name of your resource:

```shell
php artisan dok:sync:github --resource=name
```
