
- [Introduction](#introduction)
- [Search configuration](#search-configuration)
    - [Search delay](#search-delay)
    - [Min characters](#min-characters)
- [Example configuration](#example-configuration)

## Introduction {#introduction}
Live search (or instant search) is a handy feature on websites and apps that shows results as you type into the search bar. No need to hit "enter" or reload the page—it’s all happening in real time!

## Search configuration {#search-configuration}
If you followed the instructions to get manually set up, you should have `config/statamic/search.php` and add the `indexes` set up. If you don't, set it up by following those [instructions](/docs/installing#set-up-search).

This configuration controls the behavior of the search feature, including the delay before triggering the search and the minimum number of characters required.

### Search delay {#search-delay}
Configures the delay (in milliseconds) before the search is triggered after the user stops typing. This helps reduce the number of search requests sent while the user is still typing.

- **Default Value**:  
  `300` milliseconds (0.3 seconds)

- **Usage**:  
  Set this value based on the desired responsiveness of the search in `config/gaia.php`. A lower value triggers the search sooner, while a higher value may give users more time to complete their query.

  Example:
  ```php
  'search_delay' => 500, // Trigger search after 500 milliseconds
  ```

### Min characters {#min-characters}
Specifies the minimum number of characters a user must type before the search is triggered. This prevents unnecessary search queries from being made for short or incomplete input.

- **Default Value**:  
  `3` characters

- **Usage**:  
  Adjust this value based on your needs in `config/gaia.php`. For instance, if you want users to type at least 4 characters before searching, set this to 4.

  Example:
  ```php
  'min_characters' => 4, // Trigger search after 4 characters
  ```

## Example configuration {#example-configuration}

```php
'search' => [
    'search_delay' => 300,   // Delay of 300ms before search triggers
    'min_characters' => 3,   // Minimum 3 characters to start the search
],
```

This configuration provides an optimized search experience by preventing too many requests and ensuring users are searching with sufficient input.
