---
id: 6c24a2ed-e2b4-4d1e-b832-2011056e381f
blueprint: dok_4x
title: Tags
---
# Tags

:::lead
Learn about the powerful Statamic tags that come bundled.
:::

[TOC]


## `dok:outline`
Displays outline navigation generated from your markdown's headings, letting readers quickly jump between sections.

```antlers
{{ dok:outline md="{content | raw}" }}
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
| `md` | The raw, unprocessed markdown you want the navigation to be based on. Eg. `{{ markdown \| raw }}` | ✅ |
| `min` | The minimum number of headings before the component displays. | |


## `dok:index_exists`
Checks whether a search index exists. Handy for hiding search on projects that don't have an index set up yet.

{codegroup="false"}
```antlers
{{ if {dok:index_exists index="project-atlas"} }}
    {{ partial:search/search_trigger }}
{{ /if }}
```

Or pass in a pipe separated array of index names. Returns `true` if **any one** of them is found.

{codegroup="false"}
```antlers
{{ $indexes =  [
    ['label' => 'Atlas', 'value' => 'project-atlas'],
    ['label' => 'Pulse', 'value' => 'project-pulse'],
] }}

{{ if {dok:index_exists :index="indexes | pluck('value') | piped"} }}
    {{ partial:search/search_trigger }}
{{ /if }}
```


| Parameter | Description | Required |
| -------- | ------- | ------- |
| `index` | The index to look for. Takes a single name or a pipe separated list. Also available as `indexes`. | ✅ |
| `indexes` | Alias of `index`|  |

:::note
This checks your search config, not whether the index has actually been generated. An index you have configured but never run `search:update` on still counts as existing.
:::


## `dok:theme:list`
Loops every theme defined in `dok-themes.yaml`.

{codegroup="false"}
```antlers
{{ dok:theme:list }}
    <p>{{ name }} ({{ id }}) - {{ appearance }}</p>
{{ /dok:theme:list }}
```


## `dok:theme:default`
Returns the default theme for a given appearance mode, as set in the `default` object in `dok-themes.yaml`.

{codegroup="false"}
```antlers
{{ dok:theme:default mode="light" }}
    {{ name }} ({{ id }})
{{ /dok:theme:default }}
<!-- Papyrus (papyrus) -->


{{ dok:theme:default mode="light" get="id" }}
<!-- papyrus -->
```

| Parameter | Description | Required |
| -------- | ------- | ------- |
| `mode` | Which default to fetch, `light` or `dark`. Defaults to `dark`. | |
| `get` | Return a single field from the matched theme (`id`, `name`, or `appearance`).| |

## `dok:theme:manifest`
Returns the entire theme manifest in `dok-themes.yaml`.

{codegroup="false"}
```antlers
{{ dok:theme:manifest }}
    {{ themes }}
        {{ name }}, {{ id }}, {{ appearance }}
    {{ /themes}}
{{ /dok:theme:manifest }}
```


## The `project` tag
The project tags give information about the **project** and its releases.

:::tip
The project tags use the `collection` page variable, so you need to be on an entry route for them to work automatically. To use these tags outside an entry, you must use the **`collection` parameter**.

```antlers
{{ project:versions collection="my_documentation" }}
```

If nested tags overwrite the `collection` variable, use the `collection` parameter to pass in [the page scope collection](https://statamic.dev/variables#the-current-page-scope).


```antlers
{{ project:versions :collection="page:collection" }}
```
:::

:::caution
You shouldn't map the same collection to multiple releases. This tag finds the first release that contains the collection.
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
**You can also pass a wildcard to grab specific data from the entry.** This is just syntax sugar for grabbing a single piece of data without using the full tag pair.

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
**You can also pass a wildcard to grab specific data from the entry.** This is just syntax sugar for grabbing a single piece of data without using the full tag pair.

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
Gets all versions for the current project.

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

An `entry` array is also available, giving you access to any data on that release entry.

{codegroup="false"}
```antlers
{{ project:versions }}
    {{ entry }}
        {{ title }}
    {{ /entry }}
{{ /project:versions }}
```

### `project:versioned`
Returns `true` or `false` depending on whether the project has **more than one** version.

{codegroup="false"}
```antlers
{{ if {project:versioned} }}
    Stuff to display if the project has more than one version
{{ /if }}
```


## The `release` tag
The release tags give information on the **current release**.

:::tip
The release tags use the `collection` page variable, so you need to be on an entry route for them to work automatically. To use these tags outside an entry, you must use the **`collection` parameter**.

```antlers
{{ release:version collection="my_documentation" }}
```

If nested tags overwrite the `collection` variable, use the `collection` parameter to pass in [the page scope collection](https://statamic.dev/variables#the-current-page-scope).


```antlers
{{ release:version :collection="page:collection" }}
```
:::

:::caution
You shouldn't map the same collection to multiple releases. This tag finds the first release that contains the collection.
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
**You can also pass a wildcard to grab specific data from the entry.** This is just syntax sugar for grabbing a single piece of data without using the full tag pair.

{codegroup="false"}
```antlers
{{ release:entry:version }}
```
:::

### `release:nav:handle`
Gets the handle for the release navigation.

{codegroup="false"}
```antlers
{{ release:nav:handle }}
```

### `release:version`
Gets the version for the release.

{codegroup="false"}
```antlers
{{ release:version }}
```
{codegroup="false"}
```txt
1.x
```

### `release:outdated`
Returns `true` or `false` depending on whether the release is outdated.

{codegroup="false"}
```antlers
{{ if {release:outdated} }}
    This will show if the current release is outdated.
{{ /if }}
```

:::tip
This checks the `show_outdated_banner` field in your release entry.
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

