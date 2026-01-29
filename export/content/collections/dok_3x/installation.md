---
id: b5d31cb4-744b-4415-9720-49c53ca3b79a
blueprint: dok_3x
title: Installation
updated_by: cbf6fa94-2658-4dec-9152-30c80d3c652c
updated_at: 1741264233
---
# Installation

:::lead
Learn how to install Dok via the CLI or by installing into an existing site.
:::

---

**Installing Dok**

:::codegroup
{title="New site (Recommended)"}
```
statamic new mysite fawnsoftware/dok
```

{title="Existing site"}
```
php please starter-kit:install fawnsoftware/dok
```
:::/codegroup

:::important
Requires the [Statamic CLI tool](https://github.com/statamic/cli) if you're installing a new site via the `statamic` command.
:::


**After Installation**
Run the initial build process. Installing the package dependencies and building the control panel assets.

```shell
npm i && npm run cp:build
```

You can then run your dev server:

```shell
npm run dev
```

