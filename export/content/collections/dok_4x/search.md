---
id: 58cb4db1-74df-4558-beeb-fd52762d887e
blueprint: dok_4x
title: Search
use_synced_content: false
---
# Search
:::lead
Dok uses the [Documentation Search](https://statamic.com/addons/stillat/documentation-search) extension for search indexing, paired with a feature-rich, real-time search dialog.
:::/lead

## Getting Started
You'll need to create a search index if you want people to search your site.

### Adding a search index automatically
You can let Dok do this for you when running the `dok:create:release` command.

```shell
php artisan dok:create:release
```

If the project doesn't already have a search index, you'll be asked if you want to create one.

```shell
 ┌ Create a new search index for this release? ─────────────────┐
 │ ● Yes / ○ No                                                 │
 └──────────────────────────────────────────────────────────────┘
```

If the project already has a search index, you'll be asked if you want to update the search index's `searchables` array with the new release.

```shell
 ┌ Update the search index content? ────────────────────────────┐
 │ ● Yes / ○ No                                                 │
 └──────────────────────────────────────────────────────────────┘
```

:::important
The above commands assume you want to have **one index per project**. This is the way Dok is set up by default. If you want something different, you'll need to update the `dok:create:release` command script, or answer no and create indexes manually instead.
:::



### Adding a search index manually
If you're using the `dok:create:release` command, answer no to the search index questions and follow the [Documentation search](https://stillat.com/documentation-search/v1/) docs instead.


### Updating the search indexes
Update your search indexes by running:
```shell
php please search:update
```

## Search component
The search component offers a powerful search dialog, with recent & saved searches and an index selector.

Include the search on the page you want to search from:

```antlers
{{ $indexes =  [
    ['label' => 'Atlas', 'value' => 'project-atlas']
] }}

{{ partial:search/search :indexes="indexes" }}
```

### Options
You can pass options to the partial to customise it, or set the default options to use globally (anywhere that partial is used) by editing [front-matter](https://statamic.dev/variables/overview#view-front-matter) directly.

| Option | Description | Required |
|---|---|---|
| `indexes` | An array of your search index or indexes | ✅ |
| `start_index` | If you're showing more than one index, you may specify a starting index ID.  | |
| `show_index_label` | If `true`, this label shows on recent/saved results, to help identify which search index they link to. | |
| `min_chars` | The minimum number of characters required to trigger a request | |



### Index selector
The search component allows users to search from multiple indexes (or different places on your site), by allowing users to select an index from a dropdown. Pass in multiple `indexes` to the partial, or edit the partial front-matter directly to set global defaults.

```antlers
{{ $indexes =  [
    ['label' => 'Atlas', 'value' => 'project-atlas'],
    ['label' => 'Pulse', 'value' => 'project-pulse'],
] }}

{{ partial:search/search :indexes="indexes" }}
```

:::tip
All recent and saved results - regardless of which index they came from - are added to the same storage array. This means if you have multiple projects, each with their own search index, it would be hard to tell which index a result belongs to.

To fix this, change `show_index_label` to `true`. This shows the index name in the recent/saved results.

```antlers
{{ partial:search/search show_index_label="true" :indexes="indexes" }}
```
:::

### Opening
You can open the modal by dispatching a `search:open` event.

```html
<button @click="$dispatch('search:open')">
```

You'll probably want to open the search dialog with a button. One is provided in the starter kit:

```antlers
{{ partial:search/search_trigger }}
```
