- [Introduction](#introduction)
- [Using the cart API](#using-the-cart-api)
    - [Create an empty cart](#create-an-empty-cart) 
    - [Create a cart with a product](#create-cart-with-product) 
    - [Get cart contents](#get-cart-contents) 
    - [Adding to cart](#adding-to-cart) 
    - [Updating a cart line](#updating-cart-lines)
    - [Remove from cart](#remove-from-cart)
    - [Delete cart](#delete-cart)
    - [Get checkout URL](#checkout-url)
    - [Applying discount codes](#applying-discount-codes)
    - [Update cart attributes](#update-cart-attributes)
    - [Cart note](#cart-note)
    - [Buyer Identity](#buyer-identity)
- [Error handling](#error-handling)
- [Limitations](#limitations)
- [Additional](#additional)
    
## Introduction {#introduction}

The cart api is a streamlined abstraction of the Shopify Storefront API, allowing you to manage carts and handle checkout processes directly from within your website.

By default your store will make use of the Livewire cart that comes batteries included, but we understand there may be cases where users wish to use a direct API approach. 


## Key Features

- **Cart Creation and Management:** Create, retrieve, and manage shopping carts.
- **Product Management:** Add, update, or remove products in the cart.
- **Discount Handling:** Apply and manage discount codes.
- **Buyer Identity:** Manage buyer information for checkout.
- **Checkout Integration:** Generate checkout URLs.

## Using the cart API {#using-the-cart-api}

All cart management routes are protected by the `CartManagement` middleware, which ensures a cart exists before processing any request.

### Create an empty cart {#create-an-empty-cart}

Creates a new empty cart.

```bash
POST /!/gaia/cart/create/empty
```

```js
fetch('/!/gaia/cart/create/empty', {
    method: 'POST'
});
```

### Create a cart with a product {#create-cart-with-product}

Creates a new cart with a specified product.

```bash
POST /!/gaia/cart/create
```

```js
fetch('/!/gaia/cart/create', {
    method: 'POST',
    body: JSON.stringify({
        product_id: '12345',
        quantity: 2
    }),
});
```

#### Parameters

- `product_id`: (string) The ID of the product.
- `quantity`: (integer) The quantity of the product to add.


### Get cart contents {#get-cart-contents}

Retrieves the current contents of the cart.

```bash
GET /!/gaia/cart/get
```

```js
fetch('/!/gaia/cart/get');
```

###  Adding to cart {#adding-to-cart}

Adds a single product to the cart.

```bash
POST /!/gaia/cart/add
```

```js
fetch('/!/gaia/cart/add', {
    method: 'POST',
    body: JSON.stringify({
        product_id: '12345',
        quantity: 2
    }),
});
```

#### Parameters

- `product_id`: (string) The ID of the product to add.
- `quantity`: (integer) The quantity of the product to add.


###  Updating a cart line {#updating-cart-lines}

Updates the quantity of a product already in the cart.

```bash
POST /!/gaia/cart/update
```

```js
fetch('/!/gaia/cart/update', {
    method: 'POST',
    body: JSON.stringify({
        product_id: '12345',
        quantity: 2
    }),
});
```

#### Parameters

- `product_id`: (string) The ID of the product to update.
- `quantity`: (integer) The new quantity for the product.


###  Remove from cart {#remove-from-cart}

Removes specified products from the cart.

```bash
DELETE /!/gaia/cart/remove
```

```js
fetch('/!/gaia/cart/remove', {
    method: 'DELETE',
    body: JSON.stringify({
        product_ids: [1939821, 9028312]
    }),
});
```

#### Parameters

- `product_ids`: (array, required) List of product IDs to remove (maximum of 250 items).


### Delete cart {#delete-cart}

Deletes the entire cart.

```bash
DELETE /!/gaia/cart/delete
```

```js
fetch('/!/gaia/cart/delete', {
    method: 'DELETE'
});
```


### Get checkout URL {#checkout-url}

Retrieves the checkout URL for the current cart.

```bash
GET /!/gaia/cart/checkout/url
```

```js
fetch('/!/gaia/cart/checkout/url', {
    method: 'GET'
});
```

### Applying discount codes {#applying-discount-codes}

Applies discount codes to the cart.

```bash
POST /!/gaia/cart/discount
```

```js
fetch('/!/gaia/cart/discount', {
    method: 'POST',
    body: JSON.stringify({
        discount_codes: [12332, 287312, 897213812]
    }),
});
```

**Required Parameters:**
- `discount_codes`: (array) List of discount codes to apply.


### Update cart attributes {#update-cart-attributes}

Updates specific attributes of the cart.

```bash
POST /!/gaia/cart/attribute
```

```js
fetch('/!/gaia/cart/attribute', {
    method: 'POST',
    body: JSON.stringify({
        key: 'some_attribute',
        value: 'new_value'
    }),
});
```

**Required Parameters:**
- `key`: (string) The attribute key to update.
- `value`: (string) The new value for the attribute.


### Cart note {#cart-note}

Adds or updates a note on the cart.

```bash
POST /!/gaia/cart/note
```

```js
fetch('/!/gaia/cart/note', {
    method: 'POST',
    body: JSON.stringify({
        note: 'This is a cart note'
    }),
});
```

**Required Parameters:**
- `note`: (string) The note content.


### Buyer identity {#buyer-identity}

Updates the buyer's identity information.

```bash
POST /!/gaia/cart/update/identity
```

```js
fetch('/!/gaia/cart/update/identity', {
    method: 'POST',
    body: JSON.stringify({
        email: 'johndoe@example.com'
    }),
});
```
**Required Parameters:**
- `email`: (string) The attribute key to update.

## Error handling {#error-handling}

The API uses standard HTTP status codes for error responses:

- `404`: Cart not found.
- `500`: Server error.

Error responses will include a JSON object with an `error` key containing a descriptive message.

## Limitations {#limitations}

- The API operates within the limitations of the Shopify Storefront API.
- Some operations may require additional authentication or permissions based on your Shopify configuration.

## Additional {#additional}

For more detailed information on specific methods or advanced usage, please refer to the `CartRepositoryInterface` class implementation.
