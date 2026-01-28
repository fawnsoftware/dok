
### Nesting components

In AlpineJS, if you try to change a property from a nested component, it will grab that property value from the _nearest_ `x-data`. 

```html
<div x-data="yourComponent()"> 
	<div x-data="yourNestedComponent()"></div>
</div>

<script>
	function yourNestedComponent() {
		return {
			open: true,
			close() {
				// Doing this will only change the value inside yourNestedComponent
				this.open = false;
			}
		}
	}

   function yourComponent() {
	   return {
		   // This would remain unchanged
		   open: true,
	   }
   }
</script>
```

Nesting components can be confusing if there are duplicate keys. We've done our best to prevent these issues by prefixing component properties with the name of the component. This enables you to be less verbose with your naming convention.

```js
function modalComponent() {
	return {
		modalIsOpen: true,
		modalFocusAfter: null,
		modalClose() {
			this.modalIsOpen = false;
		}
	}
}

function yourComponent() {
	return {
		isOpen: true,
		focusAfter: null,
		close() {
			this.modalClose();
		}
	}
}
```


Although you won't need to use this much, if at all, we've also provided event listeners that will give you the data from any component. Lets say you've created a new modal component and nested another component inside of it:

```twig
{{ partial:components/modal/modal x-data="yourComponent()" expose_data="true" name="confirm-delete-product" }}
```

```js
function yourComponent() {
	return {
		isModalOpen() {
		
			// Event listener must come first
			window.addEventListener('modal-data-confirm-delete-product', (e) => {
				const component = e.detail.data;
				if (component.modalIsOpen) {
					console.log('The modal is open');
				} else {
					component.modalClose();
				}
			});
			
			this.$dispatch('modal-get-data-confirm-delete-product');
		}
	}
}
modal-get-data-{{ name }}
```

> [!NOTE] Note1
> In order to not have event listeners where you won't need them, this is **opt-in**. To opt-in, add `expose_data="true"` to the component. 
****