<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Markdown Parser Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may define the configuration arrays for each markdown parser.
    | You may use the base CommonMark options as well as any extensions'
    | options here. The available options are in the CommonMark docs.
    |
    | https://statamic.dev/extending/markdown#configuration
    |
    */

    'configs' => [
        'default' => [
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
            'attributes' => [
                'allow' => ['codegroup', 'collapse', 'title'],
            ],
            'code_group' => [
                'auto_group' => true,
            ],
            'heading_permalink' => [
                'symbol' => '#',
                'min_heading_level' => 2,
                'max_heading_level' => 4,
                'fragment_prefix' => '',
                'apply_id_to_heading' => true,
                'id_prefix' => '',
                'aria_hidden' => false,
            ],
            'table_of_contents' => [
                'position' => 'placeholder',
                'placeholder' => '[TOC]',
                'html_class' => 'table-of-contents not-prose',
                'min_heading_level' => 2,
                'max_heading_level' => 4,
            ],
            'external_link' => [
                'internal_hosts' => env('APP_URL'),
                'open_in_new_window' => true,
                'html_class' => 'markdown-external-link',
                'nofollow' => '',
                'noopener' => 'external',
                'noreferrer' => 'external',
            ],
        ],
    ],

];
