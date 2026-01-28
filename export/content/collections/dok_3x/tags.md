---
id: 86c3a8ce-ceb8-4ea0-9d75-6bc35033a3ac
blueprint: dok_3x
title: Tags
updated_by: cbf6fa94-2658-4dec-9152-30c80d3c652c
updated_at: 1765930496
---

# Tags

:::lead
Learn about the powerful Statamic tags that come bundled.
:::

[TOC]


## `dok:outline`
Displays outline navigation generated from the headings in your markdown file, allowing you to quickly hop between heading sections in your content.

```antlers
{{ dok:outline markdown="{content | raw}" }}
    <a href="#{{ id }}">{{ text }}</a>

    {{ if children }}
        {{ children }}
            <a href="#{{ id }}">{{ text }}</a>

                ...

        {{ /children }}
    {{ /if }}
{{ /dok:outline }}
```


| Parameter | Description | Required  |
| -------- | ------- | ------- |
| `markdown`  | The raw, unprocessed markdown you want want the navigation to be based on. Eg. `{{ markdown \| raw }}` | ✅ |
| `min` | The minimum number of headings before the component displays. | |

## The `project` tag
The project tags give information about the **project** and its releases.

:::tip
The project tags work by using the `collection` page variable, meaning you need to be on an entry route for it to work automatically. If you want to use these tags anywhere other than an entry, you must use the **`collection` parameter**.

```antlers
{{ project:versions collection="my_documentation" }}
```

If you're inside nested tags that overwrite the `collection` variable, you can use the `collection` parameter to pass in [the page scope collection](https://statamic.dev/variables#the-current-page-scope).


```antlers
{{ project:versions :collection="page:collection" }}
```
:::

:::caution
You shouldn't map the same collection to multiple releases. This tag works by finding the first release that contains the collection.
:::

### `project:entry`
Gets the current project entry.

{codegroup="false"}
```antlers
{{ project:entry }}
    {{ title }}

    {{ logo }}
        <img src="{{ url }}" />
    {{ /logo }}
{{ /project:entry }}
```

:::tip
**You can also pass in a wildcard to grab specific data from the entry.** This is just syntax sugar for when you just need to get a single piece of data from the entry but don't want to use the full tag pair.

{codegroup="false"}
```antlers
{{ project:entry:title }}

{{ project:entry:logo }}
    <img src="{{ url }}" />
{{ /project:entry:logo }}
```
:::

### `project:stable:entry`
You can set a **stable release** in the project entry. This tag returns the entry you set.

{codegroup="false"}
```antlers
{{ project:stable:entry }}
    <p>The stable version is {{ version }}</p>
{{ /project:stable:entry }}
```

:::tip
**You can also pass in a wildcard to grab specific data from the entry.** This is just syntax sugar for when you just need to get a single piece of data from the entry but don't want to use the full tag pair.

{codegroup="false"}
```antlers
Stable version is: {{ project:stable:entry:version }}
```
:::

### `project:stable:url`
Returns the **home URL** for your stable release.

{codegroup="false"}
```antlers
{{ project:stable:url }}
```
{codegroup="false"}
```txt
/docs/1.x
```

### `project:versions`
Gets all of the versions for the current project.

{codegroup="false"}
```antlers
{{ project:versions }}
    {{# Gets the version #}}
    <p>{{ version }}</p>

    {{# Gets the home URL #}}
    <p>{{ url }}</p>

    {{# Gets the label (beta, alpha, etc) #}}
    <p>{{ label }}</p>

{{ /project:versions }}
```

An `entry` array is also available, allowing you to get any data that exists on that release entry.

{codegroup="false"}
```antlers
{{ project:versions }}
    {{ entry }}
        {{ title }}
    {{ /entry }}
{{ /project:versions }}
```

### `project:versioned`
Returns `true` or `false` depending on if the project has **more than one** version.

{codegroup="false"}
```antlers
{{ if {project:versioned} }}
    Stuff to display if the project has more than one version
{{ /if }}
```


## The `release` tag
The release tags give information on the **current release**.

:::tip
The release tags work by using the `collection` page variable, meaning you need to be on an entry route for it to work automatically. If you want to use these tags anywhere other than an entry, you must use the **`collection` parameter**.

```antlers
{{ release:version collection="my_documentation" }}
```

If you're inside nested tags that overwrite the `collection` variable, you can use the `collection` parameter to pass in [the page scope collection](https://statamic.dev/variables#the-current-page-scope).


```antlers
{{ release:version :collection="page:collection" }}
```
:::

:::caution
You shouldn't map the same collection to multiple releases. This tag works by finding the first release that contains the collection.
:::

### `release:entry`
Gets the current release entry data.

{codegroup="false"}
```antlers
{{ release:entry }}
    {{ title }}
    {{ version_navigation }}
    {{ version }}
    {{ show_outdated_banner }}
    {{ github_repository_url }}
    {{ github_edit_url }}
{{ /release:entry }}
```

:::tip
**You can also pass in a wildcard to grab specific data from the entry.** This is just syntax sugar for when you just need to get a single piece of data from the entry but don't want to use the full tag pair.

{codegroup="false"}
```antlers
{{ release:entry:version }}
```
:::

### `release:nav:handle`
Gets the handle for the the release navigation.

{codegroup="false"}
```antlers
{{ release:nav:handle }}
```

### `release:version`
Gets the version for the the release.

{codegroup="false"}
```antlers
{{ release:version }}
```
{codegroup="false"}
```txt
1.x
```

### `release:outdated`
Returns `true` or `false` depending on if the release is outdated.

{codegroup="false"}
```antlers
{{ if {release:outdated} }}
    This will show if the current release is outdated.
{{ /if }}
```

:::tip
This works by looking at the `show_outdated_banner` field in your release entry.
:::

### `release:breadcrumbs`
Returns an array of breadcrumbs based on the current release navigation.

```antlers
{{ release:breadcrumbs }}
    <span>
        {{ title }}
        {{ unless last }}
            {{ svg src="arrow-right" aria-hidden="true" }}
        {{ /unless }}
    </span>
{{ /release:breadcrumbs }}
```

| Parameter | Description | Required |
| -------- |  ------- |  ------- |
| `prefix`  | Prefix the breadcrumb list with a string of your choice. Eg `Home` | |
| `prefix_single` | Uses the `prefix` only if there is **one** breadcrumb item. | |

