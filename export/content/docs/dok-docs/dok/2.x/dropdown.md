
- [Introduction](#introduction)
- [Basic Example](#basic-example) 
- [Usage](#usage) 
	- [Button](#usage-button)
	- [Dropdown Anchoring](#usage-anchoring)
	- [Styling](#usage-styling)


## Introduction {#introduction}
The dropdown component gives you an easy to use dropdown menu to use within your application. You can use the dropdown for any extra content, but usually you'll want to have extended menu links that won't fit in 
your usual layout. 

## Basic Example {#basic-example}

```twig
{{ partial:components/dropdown/dropdown }}
	{{ slot:button }}
		{{ partial:components/button text="User Menu" for="dropdown" }}
	{{ /slot:button }}
	
	{{ slot:content }}
		This is all you!
	{{ /slot:content }}
{{ /partial:components/dropdown/dropdown }}
```

## Usage {#usage}

### Button {#usage-button}
You can't have a dropdown without a button to open it, so you can use the `button` slot to add your trigger. You can either nest a button component within it or just write HTML.

To nest a button component, ensure it has the `for="dropdown"` attribute. This attribute ensures all of the AlpineJS attributes are set correctly, otherwise your dropdown won't work!
```twig
{{ partial:components/dropdown/dropdown }}
	{{ slot:button }}
		{{ partial:components/button text="User Menu" for="dropdown" }}
	{{ /slot:button }}
	
	...
```

Should you ever feel that the button component doesn't do what you need, you can go completely custom. Ensure you have the `x-ref="button" x-bind="button"` attributes on the `button` element though as the dropdown won't work without these. 
```twig
{{ partial:components/dropdown/dropdown }}
	{{ slot:button }}
		<button x-ref="button" x-bind="button">
			User Menu
		</button>
	{{ /slot:button }}
{{ /partial:components/dropdown/dropdown }}
```

### Dropdown Anchoring {#usage-anchoring}
You can change the position of the dropdown in relation to the button using the `anchor` attribute. It uses the  [AlpineJS Anchor plugin]((https://alpinejs.dev/plugins/anchor#positioning).) under the hood so you can write it just how you would if you were doing it on an HTML element (but without the `x-anchor.` prefix).

By default it uses `bottom-start`.

```twig
<!-- To show it on top end instead -->
{{ partial:components/dropdown/dropdown anchor="top-end" }}

<!-- To offset it by 50px -->
{{ partial:components/dropdown/dropdown anchor="top-end.offset.50" }}
```


## Styling {#usage-styling}
All of these methods use the awesome Tailwind Merge package, so the classes you set will overwrite the ones already on the elements. 

The dropdown component is wrapped in a 'container' element. You can style that element by using the `dropdown:class` attribute.
```twig
{{ partial:components/dropdown/dropdown container:class="block w-full py-2" }}
```

To style the dropdown container:
```twig
{{ partial:components/dropdown/dropdown dropdown:class="shadow-lg bg-slate-500" }}
```
