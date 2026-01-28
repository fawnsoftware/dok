- [Introduction](#introduction)
- [Enabling multiple collection](#enabling-multiple-collections)
- [Max wishlists](#max-wishlists)
- [Using the Wishlist API](#using-the-wishlist-api)
    - [Get User Wishlist](#get-wishlists)
    - [Create Wishlist](#create-wishlist)
    - [Rename Wishlist](#rename-wishlist)
    - [Update Wishlist](#update-wishlist)
    - [Remove from Wishlist](#remove-from-wishlist)
    - [Delete Wishlist](#delete-wishlist)
    - [Manage Wishlist Items](#manage-wishlist-items)
- [Error Handling](#error-handling)
- [Limitations](#limitations)
- [Additional Information](#additional-information)

## Introduction {#max-wishlists}
Gaia's wishlist feature allows customers to easily add and remove products. By enabling wishlist collections, customers can create and manage collections, helping them organize their saved products more effectively.

## Enabling multiple collections {#enabling-multiple-collections}
Out of the box, Gaia ships with the single wishlist enabled. You can turn on wishlist collections instead by going to `config/gaia.php` and changing the `mode` to `multiple`

```diff
'wishlists' => [
-   'mode' => 'single',
+   'mode' => 'multiple',
]
```

### Max wishlists {#max-wishlists}
By default, customers can have up to 5 collections. You can increase this within `config/gaia.php` too.

```php
'wishlists' => [
    'max_collections' => 5,

    ...
]
```

<p class="callout error">
    Reducing the number of collections after going live will result in customers wishlists being removed from the frontend.
</p>

## Using the Wishlist API {#using-the-wishlist-api}

All cart management routes are protected by the `Illuminate\Auth\Middleware\Authenticate` middleware, which ensures a user is logged in.

### Get User Wishlists {#get-wishlists}

Retrieves the current wishlists for the authenticated user.

```bash
GET /!/gaia/account/wishlists
```

```js
fetch('/!/gaia/account/wishlists');
```

### Create a New Wishlist {#create-wishlist}

Creates a new wishlist.

```bash
POST /!/gaia/account/wishlist/create
```

```js
fetch('/!/gaia/account/wishlist/create', {
    method: 'POST',
    body: JSON.stringify({
        name: 'My New Wishlist',
        product_id: '12345'
    }),
});
```

#### Parameters

- `name`: (string) The name of the wishlist.
- `product_id`: (string) The ID of the product to add.

### Update Wishlist {#update-wishlist}

Updates a wishlist to add a product.

```bash
POST /!/gaia/account/wishlist/update/
```

```js
fetch('/!/gaia/account/wishlist/update/', {
    method: 'POST',
    body: JSON.stringify({
        product_id: '12345',
        wishlist_ids: [1, 2, 3],
    }),
});
```

#### Parameters

- `product_id`: (string) The ID of the product to add.
- `wishlist_ids`: (array) The IDs of the wishlists to update.

### Manage Wishlist Items {#manage-wishlist-items}

Manages wishlist items, adding or removing products based on the wishlist IDs.

```bash
POST /!/gaia/account/wishlist/manage/
```

```js
fetch('/!/gaia/account/wishlist/manage/', {
    method: 'POST',
    body: JSON.stringify({
        product_id: '12345',
        wishlist_ids: [1, 2, 3],
    }),
});
```

#### Parameters

- `product_id`: (string) The ID of the product to manage.
- `wishlist_ids`: (array) The IDs of the wishlists to update.

### Remove Item from Wishlist {#remove-from-wishlist}

Removes an item from the wishlists.

```bash
DELETE /!/gaia/account/wishlist/removeItem/
```

```js
fetch('/!/gaia/account/wishlist/removeItem/', {
    method: 'DELETE',
    body: JSON.stringify({
        product_id: '12345',
        wishlist_ids: [1, 2, 3],
    }),
});
```

#### Parameters

- `product_id`: (string) The ID of the product to remove.
- `wishlist_ids`: (array) The IDs of the wishlists to update.

### Rename Wishlist {#rename-wishlist}

Renames a wishlist based on the ID passed as a URL Parameter. 

```bash
PATCH /!/gaia/account/wishlist/rename/{id}
```

```js
fetch('/!/gaia/account/wishlist/rename/1', {
    method: 'PATCH',
    body: JSON.stringify({
        name: 'Updated Wishlist Name',
    }),
});
```

#### Parameters

- `name`: (string) The new name of the wishlist.
- `id`: (string) The ID of the wishlist to rename.

### Delete Wishlist {#delete-wishlist}

Deletes a wishlist.

```bash
DELETE /!/gaia/account/wishlist/destroy/{id}
```

```js
fetch('/!/gaia/account/wishlist/destroy/1', {
    method: 'DELETE',
});
```

#### Parameters

- `id`: (string) The ID of the wishlist to delete.

## Error handling {#error-handling}

The API uses standard HTTP status codes for error responses:

- `404`: Wishlist not found.
- `500`: Server error.

Error responses will include a JSON object with an `error` key containing a descriptive message.

## Limitations {#limitations}

- The API operates within the limitations of the backend system's configurations.
- Some operations may require additional authentication or permissions.

