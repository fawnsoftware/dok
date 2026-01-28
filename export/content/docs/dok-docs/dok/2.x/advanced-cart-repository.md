- [Introduction](#introduction)
- [Methods Overview](#methods-overview)
    - [Retrieve Cart Identifier](#retrieve-cart-identifier)
    - [Generate Cart Storage Key](#generate-cart-storage-key)
    - [Create an Empty Cart](#create-an-empty-cart)
    - [Create a Cart with a Product](#create-a-cart-with-a-product)
    - [Retrieve Cart Details](#retrieve-cart-details)
    - [Update Cart Line Items](#update-cart-line-items)
    - [Update Buyer Identity](#update-buyer-identity)
    - [Retrieve Checkout URL](#retrieve-checkout-url)
    - [Apply Discount Codes](#apply-discount-codes)
    - [Update Cart Attributes](#update-cart-attributes)
    - [Update Cart Note](#update-cart-note)
    - [Remove Cart Lines](#remove-cart-lines)
    - [Add Lines to Cart](#add-lines-to-cart)
    - [Delete Cart](#delete-cart)
- [Error Handling](#error-handling)
- [Rate Limiting](#rate-limiting)


## Introduction {#introduction}

The **CartRepositoryInterface** defines a set of methods for interacting with the Shopify GraphQL Storefront API to manage the lifecycle of shopping carts. 

This interface provides the necessary tools to create, update, and retrieve cart details, manage buyer identity, and handle cart-specific attributes. This documentation will guide you through the available methods and their purposes, ensuring effective use of the `CartRepositoryInterface`.

## Methods Overview {#methods-overview}

### Retrieve Cart Identifier {#retrieve-cart-identifier}

```php
public function getCartId(): ?string;
```

**Description:**  
Retrieves the cart identifier. The method checks a fast in-memory store (Blink) first. If not found, it retrieves the identifier from a cookie.

**Returns:**
- `string|null` - The cart identifier or `null` if not found.

### Generate Cart Storage Key {#generate-cart-storage-key}

```php
public function getCartKey(): string;
```

**Description:**  
Generates a cart storage key using a base key (from configuration) combined with the current site's identifier. The final key is formatted as `base-key-site-id`.

**Returns:**
- `string` - The generated cart storage key.

### Create an Empty Cart {#create-an-empty-cart}

```php
public function createEmptyCart();
```

**Description:**  
Creates a new, empty cart in the Shopify Storefront. If an error occurs during creation, it is logged and an exception is thrown.

**Returns:**
- `array` - The response from the GraphQL query.

**Errors:**
- Logs errors if the cart creation fails and throws exceptions with detailed messages.

### Create a Cart with a Product {#create-a-cart-with-a-product}

```php
public function cartCreate(string $productId, int $quantity);
```

**Description:**  
Creates a cart with a specified product and quantity. Returns details such as cart ID, lines, and product variant ID.

**Parameters:**
- `productId` - (string) The ID of the product to add.
- `quantity` - (int) The quantity of the product to add.

**Returns:**
- `array` - The response from the GraphQL query.

**Errors:**
- Throws exceptions if the cart creation fails or if an invalid product ID is provided.

### Retrieve Cart Details {#retrieve-cart-details}

```php
public function cart(string $cartId);
```

**Description:**  
Retrieves details for a specific cart based on its ID. Includes information such as cart lines, attributes, and cost details.

**Parameters:**
- `cartId` - (string) The ID of the cart to retrieve.

**Returns:**
- `array` - The response from the GraphQL query containing cart details.

**Errors:**
- Throws exceptions if the cart ID is invalid or if retrieval fails.

### Update Cart Line Items {#update-cart-line-items}

```php
public function cartLinesUpdate(string $cartId, string $id, int $quantity);
```

**Description:**  
Updates the quantity of a specific item in the cart.

**Parameters:**
- `cartId` - (string) The ID of the cart.
- `id` - (string) The ID of the line item to update.
- `quantity` - (int) The new quantity for the line item.

**Returns:**
- `array` - The response from the GraphQL query.

**Errors:**
- Throws exceptions if updating the line items fails.

### Update Buyer Identity {#update-buyer-identity}

```php
public function updateCartBuyerIdentity(string $cartId, string $buyerIdentity);
```

**Description:**  
Associates customer information with a cart for personalized experiences and international pricing.

**Parameters:**
- `cartId` - (string) The ID of the cart.
- `buyerIdentity` - (string) The buyer's identity information (e.g., email).

**Returns:**
- `array` - The response from the GraphQL query.

**Errors:**
- Throws exceptions if updating buyer identity fails or if the provided information is invalid.

### Retrieve Checkout URL {#retrieve-checkout-url}

```php
public function checkoutURL(string $cartId);
```

**Description:**  
Retrieves the checkout URL for the specified cart.

**Parameters:**
- `cartId` - (string) The ID of the cart.

**Returns:**
- `string` - The checkout URL for the cart.

**Errors:**
- Throws exceptions if retrieval fails.

### Apply Discount Codes {#apply-discount-codes}

```php
public function updateCartDiscountCodes(string $cartId, array $discountCodes);
```

**Description:**  
Applies discount codes to the cart and returns relevant details.

**Parameters:**
- `cartId` - (string) The ID of the cart.
- `discountCodes` - (array) The discount codes to apply.

**Returns:**
- `array` - The response from the GraphQL query with updated cart details.

**Errors:**
- Throws exceptions if applying discount codes fails or if codes are invalid.

### Update Cart Attributes {#update-cart-attributes}

```php
public function updateCartAttributes(string $cartId, array $attributes);
```

**Description:**  
Updates the attributes of a cart, allowing storage of custom information not covered by standard cart fields.

**Parameters:**
- `cartId` - (string) The ID of the cart.
- `attributes` - (array) Key-value pairs representing the attributes to update.

**Returns:**
- `array` - The response from the GraphQL query.

**Errors:**
- Throws exceptions if updating attributes fails or if the provided attributes are invalid.

### Update Cart Note {#update-cart-note}

```php
public function updateCartNote(string $cartId, string $note);
```

**Description:**  
Adds or updates a note on the cart, which can hold additional information about the order.

**Parameters:**
- `cartId` - (string) The ID of the cart.
- `note` - (string) The content of the note.

**Returns:**
- `array` - The response from the GraphQL query.

**Errors:**
- Throws exceptions if updating the note fails.

### Remove Cart Lines {#remove-cart-lines}

```php
public function removeCartLines(string $cartId, array $lineIds);
```

**Description:**  
Removes specific line items from the cart.

**Parameters:**
- `cartId` - (string) The ID of the cart.
- `lineIds` - (array) The IDs of the line items to remove.

**Returns:**
- `array` - The response from the GraphQL query.

**Errors:**
- Throws exceptions if removal fails or if line IDs are invalid.

### Add Lines to Cart {#add-lines-to-cart}

```php
public function cartLinesAdd(string $cartId, string $productId, int $quantity);
```

**Description:**  
Adds new line items to an existing cart.

**Parameters:**
- `cartId` - (string) The ID of the cart.
- `productId` - (string) The ID of the product to add.
- `quantity` - (int) The quantity of the product to add.

**Returns:**
- `array` - The response from the GraphQL query.

**Errors:**
- Throws exceptions if adding lines fails or if the product ID is invalid.

### Delete Cart {#delete-cart}

```php
public function deleteCart(): void;
```

**Description:**  
Clears the cart by removing its identifier from cookies and the Blink cache.

**Errors:**
- Logs errors if the deletion fails.

## Error Handling {#error-handling}

Standard error handling practices apply. Errors are typically thrown as exceptions when operations fail. Ensure to handle these exceptions appropriately in your implementation. Specific exceptions from GraphQL queries are handled by the `executeGraphQLQuery` method, which throws detailed error messages based on the response.

## Rate Limiting {#rate-limiting}

Be aware of potential rate limits imposed by the Shopify GraphQL API. 

Exceeding these limits may result in errors or throttling. Consult Shopify's API documentation for detailed rate limit information.
