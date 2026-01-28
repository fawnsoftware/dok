A modal is a component that displays a dialog box to the user. It's a great way to display important information, or ask for confirmation.

- [Basic usage](#basic-usage)
- [Opening the modal](#opening-the-modal)
- [Closing the modal](#closing-the-modal)
- [Title and text](#title-and-text)
- [Alpine inside modals](#alpine-inside-modals)
- [Slots](#slots)
- [Parameters](#parameters)

## Basic usage {#basic-usage}
```twig
{{ partial:components/modal name="delete-item" title="Are you sure?" text="Are you sure you want to delete this item? This action cannot be undone."}}
	{{ slot:buttons }}
		{{ partial:components/button text="Cancel" variant="ghost" button:@click="modalClose()" }}
		{{ partial:components/button text="Delete" variant="primary" button:@click="modalClose()" }}
	{{ /slot:buttons }}
{{ /partial:components/modal }}
```

## Opening the modal {#opening-the-modal}
You open the modal by dispatching an event prefixed with `open-modal-`, followed by the modal name.

```html
<script>
	window.dispatchEvent(new CustomEvent('open-modal-delete-item'));
</script>

<!-- or from Alpine -->
<button @click="$dispatch('open-modal-delete-item')">
	Open modal
</button>
```

## Closing the modal {#closing-the-modal}
If you want to close the modal from inside the modal component using a button, you can simply use the `closeModal()` method.

```twig
<button @click="closeModal()">Close</button>
```

If you can't close it within the modal component, you can use the modal name inside an event prefixed with `close-modal-`.

```js
window.dispatchEvent(new CustomEvent('close-modal-delete-item'));
```

## Title and text {#title-and-text}
The `title` and `text` parameters are used to add a title and text to the modal.

```twig
{{ partial:components/modal 
	name="delete-item" 
	title="Are you sure?" 
	text="Are you sure you want to delete this item? This action cannot be undone."
/}}
```

If you don't want a title or text, you can leave those attributes out. This will remove the header section from the modal entirely, allowing you to be a little more flexible with the content depending on your use case. 

If you do this, for accessibility reasons, you should add a `label` attribute, with a short title explaining what the modal is for.

```twig
{{ partial:components/modal 
	name="delete-item" 
	label="Delete post confirmation"
/}}
```

## Alpine inside modals {#alpine-inside-modals}
You can use your custom Alpine components inside modals. Simply wrap the modal partial with div, and add your x-data there. We have prefixed all modal methods and variables with `modal`, so there's little risk of conflicts.

```twig
<div x-data="myAlpineComponent()">
	{{ partial:components/modal ...}}
</div>
```

## Recipes {#recipes}

## Modal with loading buttons
A modal with a confirmation button that show a loading state when clicked.

```twig
<div x-data="deleteItemComponent()">
	{{ partial:components/button text="Delete item" variant="primary" button:@click="$dispatch('open-modal-delete-item')"}}

	{{ partial:components/modal name="delete-item" title="Are you sure?" text="Are you sure you want to delete this item? This action cannot be undone."}}
		{{ slot:buttons }}
			{{ partial:components/button text="Cancel" variant="ghost" button:@click="modalClose()" }}
			{{ partial:components/button text="Delete" variant="primary" button:@click="deleteItem()" loading="isDeleting" }}
		{{ /slot:buttons }}
	{{ /partial:components/modal }}
</div>

<script>
	function deleteItemComponent() {
		return {
			isDeleting: false,
			deleteItem() {
				this.deleteLoading = true;

				setTimeout(() => {
					this.deleteLoading = false;
					window.dispatchEvent(new CustomEvent('close-modal-delete-item'));
				}, 3000);
			}
		}
	}
</script>
```


## Slots {#slots}
[Slots](https://statamic.dev/tags/partial#slots) are used to add content custom content to the modal.

### buttons
The buttons slot is in the footer of the modal.

```twig
{{ slot:buttons }}
	<button @click="close()">
		Cancel
	</button>
{{ /slot:buttons }}
```

### content
The content slot is the main content of the modal.

```twig
{{ slot:content }}
	...
{{ /slot:content }}
```

## Parameters

`name` | `string` | **required**

A unique name for your modal. This is used to identify the modal in the [open and close events](#opening-the-modal).

---

`title` | `string`

The title of the modal.

---

`text` | `string`

The text of the modal, displayed below the title.

---

`label` | `string`

A short label for the modal, used for accessibility. This is required if you don't use the `title` attribute.
