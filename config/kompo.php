<?php

return [

	'auto_classes_for_komposers' => false,

    'smart_readonly_fields' => false,

    'hide_public_model_in_forms' => true, //TODO: hide model property before display and after submit response

	'default_date_format' => 'Y-m-d',
	'default_time_format' => 'H:i',
	'default_datetime_format' => 'Y-m-d H:i',

	'files_attributes' => [
		'name' => 'name',
		'path' => 'path',
		'mime_type' => 'mime_type',
		'size' => 'size',
		'id' => 'id' //not used when files are relationships => the model's primary key is used
	],

	'places_attributes' => [
		'address' => 'address',
		'street_number' => 'street_number',
		'street' => 'street',
		'city' => 'city',
		'state' => 'state',
		'country' => 'country',
		'postal_code' => 'postal_code',
		'lat' => 'lat',
		'lng' => 'lng',
		'external_id' => 'external_id', //not the primary key, just to check unicity
	],

    'locales' => [
        //'en' => 'English',
        //'fr' => 'Français'
    ],

    'files' => [
    	'isMonogamous' => true //to review
    ]
];
