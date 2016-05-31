<?php
	//Réglages du module DescartesLogger
	$environment = [
		'prod' => [
			'DESCARTESLOGGER_ACTIVE' => true, //On active les logs
			'DESCARTESLOGGER_MIN_LEVEL' => 0, //On active les logs à partir du niveau debug
			'DESCARTESLOGGER_EMAIL' => false, //On ne défini pas d'email pour le logger et on le desactive
			'DESCARTESLOGGER_MIN_LEVEL_EMAIL' => 4, //On n'active l'envoi d'email qu'à partir du niveau ERROR
			'DESCARTESLOGGER_FILE' => 'php://stderr', //On défini le fichier de log à employer
		],
		'dev' => [
			'DESCARTESLOGGER_ACTIVE' => true, //On active les logs
			'DESCARTESLOGGER_MIN_LEVEL' => 0, //On active les logs à partir du niveau debug
			'DESCARTESLOGGER_EMAIL' => false, //On ne défini pas d'email pour le logger et on le desactive
			'DESCARTESLOGGER_MIN_LEVEL_EMAIL' => 4, //On n'active l'envoi d'email qu'à partir du niveau ERROR
			'DESCARTESLOGGER_FILE' => 'php://stderr', //On défini le fichier de log à employer
		],
		'test' => [
			'DESCARTESLOGGER_ACTIVE' => true, //On active les logs
			'DESCARTESLOGGER_MIN_LEVEL' => 0, //On active les logs à partir du niveau debug
			'DESCARTESLOGGER_EMAIL' => false, //On ne défini pas d'email pour le logger et on le desactive
			'DESCARTESLOGGER_MIN_LEVEL_EMAIL' => 4, //On n'active l'envoi d'email qu'à partir du niveau ERROR
			'DESCARTESLOGGER_FILE' => 'php://stderr', //On défini le fichier de log à employer
		]
	];
