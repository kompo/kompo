<?php

return [

    'vue_app_id' => 'app',

    'auto_classes_for_komposers' => false,

    'smart_readonly_fields' => false,

    'default_date_format'     => 'Y-m-d',
    'default_time_format'     => 'H:i',
    'default_datetime_format' => 'Y-m-d H:i',

    'locales' => [
        //'en' => 'English',
        //'fr' => 'Français'
    ],

    'eloquent_form' => [
        'return_model_as_response' => false, //if false, will return the whole Form.
        'hide_model_in_forms'      => true, //is the public $model property hidden on display or in responses?
    ],

    /**
     * Default file column names.
     */
    'files_attributes' => [
        'name'      => 'name',
        'path'      => 'path',
        'mime_type' => 'mime_type',
        'size'      => 'size',
        'id'        => 'id', //not used when files are relationships => the model's primary key is used
    ],

    /**
     * Default place column names.
     */
    'places_attributes' => [
        'address'       => 'address',
        'street_number' => 'street_number',
        'street'        => 'street',
        'city'          => 'city',
        'state'         => 'state',
        'country'       => 'country',
        'postal_code'   => 'postal_code',
        'lat'           => 'lat',
        'lng'           => 'lng',
        'external_id'   => 'external_id', //not the primary key, just to check unicity
    ],
];
