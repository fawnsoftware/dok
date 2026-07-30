---
id: 01f66a3b-0559-4cca-93bd-d645a9565ff8
blueprint: dok_4x
title: 'Content Overview'
use_synced_content: false
---
# Content Overview

:::lead
Launch new projects and releases with a single command. Then write content directly in Statamic, or import it remotely with the built-in sync utility.
:::

[TOC]

## Overview
Dok uses two distinct types to structure documentation: Projects and Releases.

**Projects** represent the top-level product or platform that documentation belongs to. A project is typically a framework, library, or addon. Laravel, React, and a Statamic addon are all examples.

**Releases** are versioned documentation sets that belong to a project. Each release represents a specific version (or range) of that project - for example `1.x` or `2.x`.

:::tip
Keep a clean Git working tree before running any commands. This lets you review changes and roll back if needed.
:::


## Creating new project
You can create a project manually, but the helper command is faster.

This generates the entries required for a project.
```shell
php artisan dok:create:project
```

## Creating a new release
You'll need a release for your project once it's created. Use the command below to scaffold everything you need.

This handles the heavy lifting - creating the collection and navigation, adding it to your project, and setting the stable release. You can even choose a route.

```shell
php artisan dok:create:release
```

:::tip
To change the fields or blueprint for new releases, edit `resources/blueprints/default-docs.yaml` manually.
:::
