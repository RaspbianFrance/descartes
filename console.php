#!/usr/bin/php
<?php
	/**
	 *	Cette page gère les scripts appelés en ligne de commande
	 */

	define('FROM_WEB', false);

	##############
	# INCLUSIONS #
	##############
	require_once(__DIR__ . '/app/load-constants.php');
	require_once(PWD . 'app/autoload.php');
	require_once(PWD . 'vendor/autoload.php');
	require_once(PWD . 'app/conn_bdd.php');
	require_once(PWD . 'app/secho.php');
	require_once(PWD . 'app/Controller.php');
	require_once(PWD . 'app/ApiController.php');
	require_once(PWD . 'app/Router.php');
	require_once(PWD . 'app/Model.php');
	require_once(PWD . 'app/Console.php');

	#########
	# MODEL #
	#########
	//On va appeler un modèle, est l'initialiser
	$db = new Model($bdd);
	
	###########
	# ROUTAGE #
	###########
	//Partie gérant l'appel des controlleurs
	$console = new Console($argv);
	$console->executeCommand($console->getCommand());
