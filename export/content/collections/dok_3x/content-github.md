---
id: 497656ca-57ad-4826-afe7-da099d74563d
blueprint: dok_3x
title: Github
updated_by: cbf6fa94-2658-4dec-9152-30c80d3c652c
updated_at: 1741267363
---
# Using GitHub

:::lead
Sync from multiple repositories, nested folders and across users/organisations. Choose to do this through the control panel, or by a command.
:::

## Getting Started
Dok makes it easy to use GitHub as the source for your documentation. You can even mix content sources across collections, you aren't tied to one or the other. This is great when you host your docs within the same repository as your product.

You can even sync content from _different_ owners and organisations as long as your personal access token has the correct permissions.

:::important
Assumes you have already created a GitHub personal access token. [Learn how to create a personal access token.](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens#creating-a-fine-grained-personal-access-token)
:::

To get started, you'll want to add a new value to your config inside of `config/dok.php`. The below is an example. You may need to clear your Laravel cache after changing this.

{title="config/dok.php"}
```php
'resources' => [
  'your_project' => [

      'source' => 'github'

       // The reposistory to sync from.
       'repo' => 'owner/repo',

       // The branch to sync from.
       'branch' => 'main',

       // An array of folders to sync.
       // The below would only sync the docs folder.
       // Leave empty to import everything.
       'content' => ['docs'],

       // The env variable for your Github personal access token.
       'token' => env('GITHUB_SYNC_TOKEN'),
  ],
],
```

You can now sync that resource. For entries where you want to use this content, toggle **Use synced content** to `true` and select your resource file from the dropdown.

## Syncing
To sync your resource through the control panel, head over to the **Utilities page**, under **Remote Sync**.

You can run the following command to sync a resource, using an interative UI:

```shell
php artisan dok:sync:github
```

Skip the interactive stage by providing a `--resource`. Replace `name` with the name of your resource:

```shell
php artisan dok:sync:github --resource=name
```


