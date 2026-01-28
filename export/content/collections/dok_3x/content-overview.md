---
id: de4f9439-e246-4532-8cd7-141aff07a0cf
blueprint: dok_3x
title: 'Content Overview'
updated_by: cbf6fa94-2658-4dec-9152-30c80d3c652c
updated_at: 1769275153
use_synced_content: false
---
# Content Overview

:::lead
Learn how to launch new projects and releases with a simple command, then write content directly in Statamic or import it remotely using the built-in sync utility.
:::

[TOC]

## Overview
Dok uses two distinct types to structure documentation: Projects and Releases.

**Projects** represent the top-level product or platform that documentation belongs to. A project is typically a framework, library, or addon. Laravel, React, and a Statamic Addon are all examples of a project.

**Releases** are versioned documentation sets that belong to a project. Each release represents a specific version (or version range) of that project, such as `1.x`, `2.x` etc.

:::tip
It's recommended to have a clean GIT working tree before running any commands. This allows you to view the changes and roll back if needed.
:::


## Creating new project
Although you can create a project manually, its recommended to use the helper command to get it set up quickly.

This generates the nessessary entries required for a project.
```shell
php artisan dok:create:project
```

## Creating a new release
You'll need a release for your project once it's created. You can use the command below to scaffold all of the resources you need to get you started.

This does all of the heavy lifting and will create the collection, navigation, add it to your project, set the stable release, and you can even choose a route.

```shell
php artisan dok:create:release
```

:::tip
If you want to change the fields or blueprint that comes with newly created releases, you will need to edit `resources/blueprints/default-docs.yaml` manually.
:::
