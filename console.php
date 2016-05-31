#!/usr/bin/php
<?php
	###############
	# ENVIRONMENT #
	###############
	define('ENVIRONMENT', 'dev');
	define('FROM_WEB', false);
	require_once(__DIR__ . '/descartes/load-environment.php');

	##############
	# INCLUSIONS #
	##############
	//On va inclure l'ensemble des fichiers necessaires
	require_once(PWD . '/descartes/autoload.php');
	require_once(PWD . '/vendor/autoload.php');
	require_once(PWD . '/descartes/Console.php');
	require_once(PWD . '/routes.php');

	#########
	# MODEL #
	#########
	//On va appeler un modèle, est l'initialiser
	$bdd = Model::connect(DATABASE_HOST, DATABASE_NAME, DATABASE_USER, DATABASE_PASSWORD);

	###########
	# ROUTAGE #
	###########
	//Partie gérant l'appel des controlleurs
	$console = new \Console($argv);
	$console->executeCommand($console->getCommand());
