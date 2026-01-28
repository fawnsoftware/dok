---
id: b5d31cb4-744b-4415-9720-49c53ca3b79a
blueprint: dok_3x
title: Installation
updated_by: cbf6fa94-2658-4dec-9152-30c80d3c652c
updated_at: 1741264233
---
# Installation

:::lead
Learn how to install Dok via the CLI or by installing into a new site.
:::

---

:::caution title="Dok only supports ^6.0.0-beta4 installations"
Dok has been updated and released to support the v6.0 beta. This should cause minimal impact, but if you are installing from `statamic new my-site fawnsoftware/dok`, things won't work as you imagined.

**Read the guide below to install into the latest beta.**
:::


Install via the cli as normal:

```shell
statamic new my-site fawnsoftware/dok
```

:::note
You may get the error `Unknown searchable [content]`. You can safely ignore this as the config is for a v6 installation which we'll install next.
:::

Navigate to your newly created site in your terminal and require the latest Statamic beta:

```shell
composer require statamic/cms:^6.0@beta --with-all-dependencies
```

You're now ready to get started! But first, you'll need to install the node packages, build the control panel assets, and run your dev server:

```shell
npm i && npm run cp:build && npm run dev
```
