- [Introduction](#introduction)
- [Methods Overview](#methods-overview)
    - [Retrieve User Wishlists](#retrieve-user-wishlists)
    - [Create a Wishlist](#create-a-wishlist)
    - [Update Wishlist with Product](#update-wishlist-with-product)
    - [Rename a Wishlist](#rename-a-wishlist)
    - [Delete a Wishlist](#delete-a-wishlist)
    - [Remove Item from Wishlist](#remove-item-from-wishlist)
    - [Manage Wishlist Items](#manage-wishlist-items)
- [Error Handling](#error-handling)

## Introduction {#introduction}

The **WishlistRepositoryInterface** provides methods for managing user wishlists in Gaia. 

This interface allows you to create, update, delete, and retrieve wishlists, as well as manage individual items within them. This document outlines the available methods, detailing their purpose and usage.

## Methods Overview {#methods-overview}

### Retrieve User Wishlists {#retrieve-user-wishlists}

```php
public function getUserWishlists();
```

**Description:**  
Retrieves all wishlists associated with the current user.

**Returns:**
- `array` - An array of wishlists for the user.

### Create a Wishlist {#create-a-wishlist}

```php
public function createWishlist(array $wishlistDetails): void;
```

**Description:**  
Creates a new wishlist for the current user based on the provided details.

**Parameters:**
- `wishlistDetails` - (array) The details of the wishlist, such as name and description.

**Errors:**
- Throws an exception if the wishlist creation fails.

### Update Wishlist with Product {#update-wishlist-with-product}

```php
public function updateWishlist(array $wishlistIds, string $productId): void;
```

**Description:**  
Updates one or more wishlists by adding a product to them based on the provided product ID.

**Parameters:**
- `wishlistIds` - (array) An array of wishlist IDs to update.
- `productId` - (string) The ID of the product to add to the wishlist.

**Errors:**
- Throws exceptions if the wishlist or product is not found, or if the update fails.

### Rename a Wishlist {#rename-a-wishlist}

```php
public function renameWishlist(string $wishlistId, string $newName): void;
```

**Description:**  
Renames a wishlist for the current user.

**Parameters:**
- `wishlistId` - (string) The ID of the wishlist to rename.
- `newName` - (string) The new name for the wishlist.

**Errors:**
- Throws an exception if renaming the wishlist fails.

### Delete a Wishlist {#delete-a-wishlist}

```php
public function deleteWishlist(string $wishlistId): void;
```

**Description:**  
Deletes a wishlist for the current user.

**Parameters:**
- `wishlistId` - (string) The ID of the wishlist to delete.

**Errors:**
- Throws an exception if the wishlist deletion fails.

### Remove Item from Wishlist {#remove-item-from-wishlist}

```php
public function removeWishlistItem(array $wishlistIds, string $productId): void;
```

**Description:**  
Removes a specific product from one or more wishlists.

**Parameters:**
- `wishlistIds` - (array) The IDs of the wishlists from which to remove the product.
- `productId` - (string) The ID of the product to remove from the wishlists.

**Errors:**
- Throws exceptions if the product or wishlist is not found, or if the removal fails.

### Manage Wishlist Items {#manage-wishlist-items}

```php
public function manageWishlistItems(array $wishlistIds, string $productId): void;
```

**Description:**  
Synchronizes the product across multiple wishlists by adding new items and removing old ones, effectively managing the contents of wishlists.

**Parameters:**
- `wishlistIds` - (array) An array of wishlist IDs to manage.
- `productId` - (string) The ID of the product to update across the wishlists.

**Errors:**
- Throws exceptions if the product or wishlist is not found, or if the update fails.

## Error Handling {#error-handling}

Standard error handling is implemented for wishlist-related operations. Exceptions are thrown if operations fail, and detailed error messages provide insight into the cause of the failure. Ensure that proper exception handling is in place within your application to manage potential errors.
