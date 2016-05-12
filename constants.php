<?php
	/*
		This file define customisable constants and options
	*/

	/* Settings definition */

	//If true, application is considered as in production
	define('ENV_PRODUCTION', true);

	//If true, caching mechanisms are activated
	define('ACTIVATING_CACHE', true); //On desactive le cache


	/* Define default comportment */

	//Default controller to call
	define('DEFAULT_CONTROLLER', 'index');

	//Default method to call
	define('DEFAULT_METHOD', 'byDefault');

	//Réglages des identifiants de base de données
	define('DATABASE_HOST', 'localhost'); //Hote de la bdd
	define('DATABASE_NAME', 'descartes_example'); //Nom de la bdd
	define('DATABASE_USER', 'root'); //Utilisateur de la bdd
	define('DATABASE_PASSWORD', 'ajani7725'); //Password de l'utilisateur

	/*******************************/
	/* PART FOR YOUR OWN CONSTANTS */
	/*******************************/

