<?php

return [
    'hidden' => [
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ],

    'types' => [
        'text',
        'number',
        'email',
        'tel',
        'url',
        'boolean',
        'tags',
        'image',
        'one_image',
        'images',
        'featured_images',
        'password',
        'textarea',
        'editor',
        'code_editor',
        'countries',
        'treeview',
        'children',
        'date',
        'enum',
        'enum_relation',
        'multi_select',
        'nested',
        'relation_key',
        'select',
        'custom',
    ],

    'phone_columns' => [
        'phone',
        'secondary_phone',
        'fax',
        'mobile'
    ],

    'image_columns' => [
        'image',
        'photo',
        'avatar'
    ],

    'integer_columns' => [
        'bigint',
        'int',
        'integer',
        'nested'
    ],

    'properties' => [
        'section',
        'name',
        'slug',
        'type',
        'thName',
        'title',
        'table',
        'class',
        'translatable',
        'required',
        'disabled',
        'searchable',
        'sortable',
        'showInTable',
        'showInForm',
        'subTitleFields',
        'treeview',
        'nested',
        'value',
        'min',
        'max',
        'validations',
    ],
];
