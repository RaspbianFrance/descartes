<?php
	/*
		Ce fichier défini les constantes modifiables et les options
	*/

	//On défini l'environment
	$env = [
		'prod' => [
			//Si vrai, on active le cache
			'ACTIVATING_CACHE' => true,

			//On défini le nom de la session
			'SESSION_NAME' => 'prod_descartes',

			//Configuration de la base de données
			'DATABASE_HOST' => 'localhost',
			'DATABASE_NAME' => 'descartes_example',
			'DATABASE_USER' => 'root',
			'DATABASE_PASSWORD' => 'root',
		],
		'dev' => [
			//Si vrai, on active le cache
			'ACTIVATING_CACHE' => true,

			//On défini le nom de la session
			'SESSION_NAME' => 'dev_descartes',

			//Configuration de la base de données
			'DATABASE_HOST' => 'localhost',
			'DATABASE_NAME' => 'descartes_example',
			'DATABASE_USER' => 'root',
			'DATABASE_PASSWORD' => 'root',
		],
		'test' => [
			//Si vrai, on active le cache
			'ACTIVATING_CACHE' => true,

			//On défini le nom de la session
			'SESSION_NAME' => 'test_descartes',

			//Configuration de la base de données
			'DATABASE_HOST' => 'localhost',
			'DATABASE_NAME' => 'descartes_example',
			'DATABASE_USER' => 'root',
			'DATABASE_PASSWORD' => 'root',
		]
	];

