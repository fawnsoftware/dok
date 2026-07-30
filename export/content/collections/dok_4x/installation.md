---
id: 6bc83fc8-5378-45c1-bdb3-9f180fddb1d2
blueprint: dok_4x
title: Installation
---
# Installation

:::lead
Learn how to install Dok via the CLI or into an existing site.
:::

---

**Installing Dok using the CLI**

```shell
statamic new mysite fawnsoftware/dok
```

:::important
Requires the [Statamic CLI tool](https://github.com/statamic/cli) if you're installing a new site via the `statamic` command.
:::

**Installing Dok into an existing site**

```shell
php please starter-kit:install fawnsoftware/dok
```


**After Installation**
Run the initial build process - this installs dependencies and builds the control panel assets.

```shell
npm i && npm run cp:build
```

You can then run your dev server:

```shell
npm run dev
```

