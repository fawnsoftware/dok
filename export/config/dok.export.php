<?php

return [

    'markdown' => [

        /*
        |--------------------------------------------------------------------------
        | Component Bindings
        |--------------------------------------------------------------------------
        |
        | You can bind CommonMark container blocks to Laravel components here.
        | Read more about Component Binding in the Dok documentation.
        |
        */

        'components' => [
            'note' => [
                'template' => 'components.hint.hint',
            ],
            'tip' => [
                'template' => 'components.hint.hint',
            ],
            'important' => [
                'template' => 'components.hint.hint',
            ],
            'caution' => [
                'template' => 'components.hint.hint',
            ],
            'warning' => [
                'template' => 'components.hint.hint',
            ],
            'lead' => [
                'template' => 'components.lead.lead',
            ],
            'card' => [
                'template' => 'components.card.card',
                'slots' => ['cta-text'],
            ],
            'cardgroup' => [
                'template' => 'components.card-group.card-group',
            ],
            'accordion' => [
                'template' => 'components.accordion.accordion',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Torchlight Options
        |--------------------------------------------------------------------------
        |
        | If you enabled Torchlight for syntax highlighting when installing
        | the starterkit, you can update the options for that here.
        |
        */

        'torchlight' => [
            'enabled' => env('TORCHLIGHT_ENABLED', true),

            'theme' => env('TORCHLIGHT_THEME', 'olaolu-palenight'),

            'options' => [
                'lineNumbers' => false,
                'lineNumbersStart' => 1,
                'lineNumberAndDiffIndicatorRightPadding' => 2,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | If you're using Github to sync your content, you can add your resources
    | here.
    |
    */

    'resources' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Location
    |--------------------------------------------------------------------------
    |
    | When syncing your resources from GitHub or other providers, you can
    | choose where on your filesystem the files should live. This should
    | be relative to the root of your project, as methods that use this
    | config use base_path under the hood.
    |
    */

    'resource_location' => base_path('content/docs'),

    /*
    |--------------------------------------------------------------------------
    | Create Routes
    |--------------------------------------------------------------------------
    |
    | When creating a new release you will get to pick which route you want
    | to use. You can customise that list here by adding more options
    | or commenting out ones you do not wish to use.
    |
    | You can always customise the release route later in the
    | collection settings, under routes.
    |
    | <PROJECT_NAME> will be replaced with the slugified name of your project.
    | <VERSION> will be replaced with the slugified version of the release.
    |
    | The key is the route that will be used.
    | The value is the human-friendly name.
    |
    */
    'create_routes' => [
        "{{ !parent_uri ?= '<VERSION>/' }}{{ parent_uri }}/{{ slug }}" => '<VERSION>/...',
        "{{ !parent_uri ?= '<PROJECT_NAME>/<VERSION>/' }}{{ parent_uri }}/{{ slug }}" => '<PROJECT_NAME>/<VERSION>/...',
        "{{ !parent_uri ?= 'docs/' }}{{ parent_uri }}/{{ slug }}" => 'docs/...',
        "{{ !parent_uri ?= 'docs/<VERSION>/' }}{{ parent_uri }}/{{ slug }}" => 'docs/<VERSION>/...',
        "{{ !parent_uri ?= 'docs/<PROJECT_NAME>/<VERSION>/' }}{{ parent_uri }}/{{ slug }}" => 'docs/<PROJECT_NAME>/<VERSION>/...',
        '{{ parent_uri }}/{{ slug }}' => '/...',
    ],
];
