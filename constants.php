<?php
	/*
		Ce fichier défini les constantes modifiables et les options
	*/

	/* Défintion des réglages */

	//On defini le nom de session
	define('SESSION_NAME', 'descartes_exemple_website');

	//Si vraie, l'applie est en prod
	define('ENV_PRODUCTION', true);

	//Si vrai, on active les méchanismes de cache
	define('ACTIVATING_CACHE', true); //On desactive le cache

	//Réglages des identifiants de base de données
	define('DATABASE_HOST', 'localhost'); //Hote de la bdd
	define('DATABASE_NAME', 'descartes_example'); //Nom de la bdd
	define('DATABASE_USER', 'root'); //Utilisateur de la bdd
	define('DATABASE_PASSWORD', 'root'); //Password de l'utilisateur

	/**************************************/
	/* PARTIE POUR VOS PROPRES CONSTANTES */
	/**************************************/

