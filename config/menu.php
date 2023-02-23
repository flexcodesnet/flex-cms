<?php

return [
    [
        'title'  => 'messages.fields.dashboard',
        'icon'   => 'fas fa-tachometer-alt',
        'href'   => 'panel.index',
        'active' => 'panel.index',
    ],
    [
        'children' => [
            [
                'title' => 'messages.general',
                'icon'  => 'far fa-folder-open',
                'menus' => [
//                    [
//                        'title'  => 'messages.models.plural.pages',
//                        'icon'   => 'far fa-file',
//                        'href'   => 'panel.pages.index',
//                        'active' => 'panel.pages.*',
//                    ],
//                    [
//                        'title'  => 'messages.models.plural.services',
//                        'icon'   => 'fa-hand-holding-medical',
//                        'href'   => 'panel.services.index',
//                        'active' => 'panel.services.*',
//                    ],
//                    [
//                        'title'  => 'messages.models.plural.faqs',
//                        'icon'   => 'fa-question-circle',
//                        'href'   => 'panel.faqs.index',
//                        'active' => 'panel.faqs.*',
//                    ],

//                    [
//                        'title'  => 'messages.models.plural.menus',
//                        'icon'   => 'fa-stream',
//                        'href'   => 'panel.menus.index',
//                        'active' => 'panel.menus.*',
//                    ],
                ]
            ],
            [
                'title' => 'messages.properties',
                'icon'  => 'far fa-folder-open',
                'menus' => [
//                    [
//                        'title'  => 'messages.models.plural.properties',
//                        'icon'   => 'far fa-file',
//                        'href'   => 'panel.properties.index',
//                        'active' => 'panel.properties.*',
//                    ],
//                    [
//                        'title'  => 'messages.models.plural.list_options',
//                        'icon'   => 'fa-hand-holding-medical',
//                        'href'   => 'panel.list_option_groups.index',
//                        'active' => 'panel.list_option_groups.*',
//                    ]
//                    [
//                        'title'  => 'messages.models.plural.menus',
//                        'icon'   => 'fa-stream',
//                        'href'   => 'panel.menus.index',
//                        'active' => 'panel.menus.*',
//                    ],
                ]
            ]
        ],
    ],
    [
        'title'    => 'messages.blog_management',
        'icon'     => 'fa-file',
        'children' => [
            [
//                'title'  => 'messages.models.plural.posts',
//                'icon'   => 'fas fa-sticky-note',
//                'active' => 'panel.posts.*',
                'menus'  => [
//                    [
//                        'title'  => 'messages.models.plural.posts',
//                        'icon'   => 'fas fa-sticky-note',
//                        'href'   => 'panel.posts.index',
//                        'active' => 'panel.posts.index',
//                    ],
//                    [
//                        'title'  => 'messages.models.create.posts',
//                        'icon'   => 'fas fa-plus-circle',
//                        'href'   => 'panel.posts.add',
//                        'active' => 'panel.posts.add',
//                    ],
//                    [
//                        'title'  => 'messages.models.plural.categories',
//                        'icon'   => 'fa-th',
//                        'href'   => 'panel.categories.index',
//                        'active' => 'panel.categories.index',
//                    ],
//                    [
//                        'title'  => 'messages.models.plural.tags',
//                        'icon'   => 'fa-tags',
//                        'href'   => 'panel.tags.index',
//                        'active' => 'panel.tags.*',
//                    ],
                ]
            ],
        ],
    ],
    [
        'title'    => 'messages.fields.manage_settings',
        'children' => [
            [
                'title'  => 'messages.models.plural.settings',
                'icon'   => 'fa-cogs',
                'href'   => 'panel.settings.index',
                'active' => 'panel.settings.index',
            ],
//            [
//                'title'  => 'messages.models.plural.subscribers',
//                'icon'   => 'fa-users',
//                'href'   => 'panel.subscribers.index',
//                'active' => 'panel.subscribers.*',
//            ],
//            [
//                'title'  => 'messages.models.plural.leads',
//                'icon'   => 'fa-headphones-alt',
//                'href'   => 'panel.leads.index',
//                'active' => 'panel.leads.*',
//            ],
        ],
    ],

    [
        'title'    => 'messages.fields.manage_users',
        'children' => [
//            [
//                'title'  => 'messages.models.plural.plugins',
//                'icon'   => 'fa-plug',
//                'href'   => 'panel.plugins.index',
//                'active' => 'panel.plugins.*',
//            ],
            [
                'title'  => 'messages.models.plural.users',
                'icon'   => 'fa-user',
                'href'   => 'panel.users.index',
                'active' => 'panel.users.*',
            ],
            [
                'title'  => 'messages.models.plural.roles',
                'icon'   => 'fa-user-tag',
                'href'   => 'panel.roles.index',
                'active' => 'panel.roles.*',
            ],
            [
                'title'  => 'messages.models.plural.permissions',
                'icon'   => 'fa-user-lock',
                'href'   => 'panel.permissions.index',
                'active' => 'panel.permissions.*',
            ],
        ],
    ],
];
