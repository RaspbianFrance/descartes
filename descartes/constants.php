<?php
	/*
		Ce fichier définit les constantes du MVC
	 */

	//Pour les chemin, la norme est de ne pas mettre de '/' final

	//On définit l'arborescence URL pour accéder à la racine du site (typiquement, si votre site est un dossier et ne dispose pas d'un nom de domaine dédié)
	$urlDirPath = '/descartes';

	//On calcul les chemins de base du système
	$protocol = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://';
	$serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
	$port = ($_SERVER['SERVER_PORT'] == 80) ? '' : $_SERVER['SERVER_PORT'];

	define('PWD', substr(__DIR__, 0, strrpos(__DIR__, '/'))); //On défini le chemin de base du site

	var_dump(PWD);

	define('HTTP_PWD', $protocol . $serverName . $port . $urlDirPath); //On défini l'adresse url du site

	//On définit le chemin des ressources back
	define('PWD_CONTROLLER', PWD . '/controllers'); //Dossier des controllers
	define('PWD_MODEL', PWD . '/model'); //Dossier des models
	define('PWD_TEMPLATES', PWD . '/templates'); //Dossier des templates
	define('PWD_MODULES', PWD . '/modules'); //Dossier des modules
	define('PWD_CACHE', PWD . '/cache'); //Dossier du cache


	//On définit le chemin des ressources front
	define('PWD_ASSETS', PWD . '/assets'); //Chemin du dossier des assets
	define('HTTP_PWD_ASSETS', HTTP_PWD . '/assets'); //URL du dossier des assets

	define('PWD_IMG', PWD_ASSETS . '/img'); //Chemin dossier des images
	define('HTTP_PWD_IMG', HTTP_PWD_ASSETS . '/img'); //URL dossier des images

	define('PWD_CSS', PWD_ASSETS . '/css'); //Chemin dossier des css
	define('HTTP_PWD_CSS', HTTP_PWD_ASSETS . '/css'); //URL dossier des css

	define('PWD_JS', PWD_ASSETS . '/js'); //Chemin dossier des js
	define('HTTP_PWD_JS', HTTP_PWD_ASSETS . '/js'); //URL dossier des js


