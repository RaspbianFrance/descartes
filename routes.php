<?php
    $routes = array(
		'Index' => [
			'home' => '/',
			'show_value' => [
				'/show-value/{first_value}/',
				'/show-value/{first_value}/{second_value}/',
			],
        ],
    );

    define('ROUTES', $routes);
