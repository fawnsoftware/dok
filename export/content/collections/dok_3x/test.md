---
id: bac29692-c875-4eb4-b2d4-c54e53370da1
blueprint: dok_3x
title: Test
use_synced_content: false
updated_by: cbf6fa94-2658-4dec-9152-30c80d3c652c
updated_at: 1769275095
---
TODO

[Decimal HTML Character References](&#106;&#97;&#118;&#97;&#115;&#99;&#114;&#105;&#112;&#116;&#58;&#97;&#108;&#101;&#114;&#116;&#40;&#39;&#88;&#83;&#83;&#39;&#41;)

[Decimal HTML Character References Without Trailing Semicolons](&#0000106&#0000097&#0000118&#0000097&#0000115&#0000099&#0000114&#0000105&#0000112&#0000116&#0000058&#0000097&#0000108&#0000101&#0000114&#0000116&#0000040&#0000039&#0000088&#0000083&#0000083&#0000039&#0000041)

[Hexadecimal HTML Character References Without Trailing Semicolons](&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x61&#x6C&#x65&#x72&#x74&#x28&#x27&#x58&#x53&#x53&#x27&#x29)

[Embedded Tab](jav   ascript:alert('XSS'))

[sadsd](jav&#x09;ascript:alert(conso))

[asdasdsad](   javascript:alert('XSS')   )


what **the heck**


:::card title="<script>alert('XSS2')</script>"
what **the heck**
:::/card

### Test 1: Basic JavaScript
:::card {href="javascript:alert('XSS')"}
Should be blocked - renders as DIV
:::

### Test 2: JavaScript with encoding
:::card {href="jAvAsCrIpT:alert('XSS')"}
Should be blocked - case variation
:::

### Test 3: Data URI
:::card {href="data:text/html,<script>alert('XSS')</script>"}
Should be blocked
:::

### Test 4: VBScript
:::card {href="vbscript:alert('XSS')"}
Should be blocked
:::

### Test 5: File protocol
:::card {href="file:///etc/passwd"}
Should be blocked
:::

### Test 6: JavaScript with HTML entities
:::card {href="java&#x09;script:alert('XSS')"}
Should be blocked - tab character
:::

### Test 7: JavaScript with line break
:::card {href="java
script:alert('XSS')"}
Should be blocked - newline
:::

### Test 8: JavaScript with null byte
:::card {href="javascript&#x00;:alert('XSS')"}
Should be blocked
:::

### Test 9: Safe HTTPS URL (Control)
:::card {href="https://example.com"}
Should WORK - renders as A tag
:::

### Test 10: Safe relative URL (Control)
:::card {href="/dashboard"}
Should WORK - renders as A tag
:::

### Test 11: Safe mailto (Control)
:::card {href="mailto:test@example.com"}
Should WORK if mailto is allowed
:::

## Attribute Injection Tests

### Test 12: Event handler in title
:::card {title="test\" onload=\"alert('XSS')"}
Should be escaped - no alert
:::

### Test 13: Event handler in custom attribute
:::card {data-custom="test\" onclick=\"alert('XSS')"}
Should be escaped
:::

### Test 14: Script in title
:::card {title="<script>alert('XSS')</script>"}
Should be escaped - displays as text
:::

### Test 15: Image onerror in title
:::card {title="<img src=x onerror=alert('XSS')>"}
Should be escaped
:::

### Test 16: Style injection
:::card {title="test\" style=\"position:fixed;top:0;left:0;width:100%;height:100%;background:red;z-index:9999"}
Should be escaped
:::

## Icon Path Tests

### Test 17: Path traversal
:::card {icon="../../etc/passwd"}
Should be blocked
:::

### Test 18: Path traversal with encoding
:::card {icon="..%2f..%2fetc%2fpasswd"}
Should be blocked
:::

### Test 19: Safe icon (Control)
:::card {icon="icon/info"}
Should WORK
:::

## Slot Content Tests

### Test 20: Script tag in slot
:::card
<script>alert('XSS 20')</script>
:::


### Test 21: Image onerror in slot
:::card
<img src=x onerror="alert('XSS 21')">
:::


### Test 22: SVG with script
:::card
<svg onload="alert('XSS 22')">
:::

### Test 23: Iframe injection
:::card
<iframe src="javascript:alert('XSS 23')">
:::


### Test 24: Event handler in slot
:::card
<div onclick="alert('XSS 24')">Click me</div>
:::



## Edge Cases

### Test 27: Multiple dangerous attributes
:::card {href="javascript:alert(1)" title="<script>alert(2)</script>" onclick="alert(3)"}
All should be blocked/escaped
:::

### Test 28: Empty href
:::card {href=""}
Should render as DIV
:::

### Test 29: Whitespace in href
:::card {href="   javascript:alert('XSS')   "}
Should be blocked (after trim)
:::

### Test 30: Unicode in JavaScript
:::card {href="\u006a\u0061\u0076\u0061\u0073\u0063\u0072\u0069\u0070\u0074:alert('XSS')"}
Should be blocked
:::

### Test 31: HTML entities in href
:::card {href="&#106;&#97;&#118;&#97;&#115;&#99;&#114;&#105;&#112;&#116;&#58;alert('XSS')"}
Should be blocked
:::

### Test 32: Mixed case with encoding
:::card {href="JaVaScRiPt&#58;alert('XSS')"}
Should be blocked
:::




:::cardgroup

:::card icon="icon/download" title="Installation" href="/docs/3.x/installation"
Get started with writing your documentation site.

:::slot.cta
Get started with Dok
:::/slot
:::/card

:::card icon="icon/coins" title="Purchase" href="https://statamic.com/starter-kits/fawn/dok-documentation"
Not got a licence yet? You can buy Dok from the Statamic marketplace.

:::slot.cta
View on the marketplace
:::/slot

:::/card

:::/cardgroup

<!--
:::tip
:::codegroup
{title="asodoasdj"}
```php
'banner' => [
    'template' => 'components.banner.index',
    'slots' => ['footer'],
],
```

{collapse="true"}
```php
'banner' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'alert' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'blip' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'asdad' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'banner' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'alert' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'blip' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'asdad' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'banner' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'alert' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'blip' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'asdad' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
```
:::/codegroup
:::/tip -->

<!--

:::card foo="bar" bacon eggs=tasty tomato='"juice"'
asdmoasmdoasd
:::/card

:::codegroup
{title="asodoasdj"}
```php
'banner' => [
    'template' => 'components.banner.index',
    'slots' => ['footer'],
],
```

{collapse="true"}
```php
'banner' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'alert' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'blip' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
'asdad' => [
    'template' => 'components.banner.index',
    'slots' => ['asd'],
],
```
:::/codegroup

You can now use the slot inside of your banner block:

```markdown
:::banner

:::slot.footer
This is my footer slot! You can write markdown
here just like you would anywhere else.
:::/slot

:::/banner
```

The `footer` slot will now return the rendered markdown:
```blade
@if ($footer)
    {{ $footer }}
@endif
``` -->



<!--
:::foo
Card body content
:::/foo



::::important
Stars are giant balls of super-hot gas that make their own light and heat.



:::codegroup
{title="space.js" collapse="false"}
```js
async function getSpaceData() {
    try {
        const res = await fetch('/api/space');
        const data = await res.json();
        console.log(data);
    } catch (err) {
        console.error(err);
    }
}
```

{title="result" collapse="true"}
```js
{
    "planets": [
        "Mercury",
        "Venus",
        "Earth",
        "Mars",
        "Jupiter",
        "Saturn",
        "Uranus",
        "Neptune"
    ],
    "galaxies": [
        "Milky Way",
        "Andromeda",
        "Triangulum",
        "Whirlpool",
        "Sombrero"
    ]
}

```
:::

| Syntax | Description |
| ----------- | ----------- |
| Header | Title |
| Paragraph | Text |

:::: -->