<?php
	session_start();
	define('FROM_WEB', true);

	//On creé le csrf token si il n'existe pas
	if (!isset($_SESSION['csrf']))
	{
		$_SESSION['csrf'] = str_shuffle(uniqid().uniqid());
	}

	##############
	# INCLUSIONS #
	##############
	//On va inclure l'ensemble des fichiers necessaires
	require_once(__DIR__ . '/app/load-constants.php');
	require_once(PWD . 'app/autoload.php');
	require_once(PWD . 'vendor/autoload.php');
	require_once(PWD . 'app/conn_bdd.php');
	require_once(PWD . 'app/secho.php');
	require_once(PWD . 'app/Controller.php');
	require_once(PWD . 'app/ApiController.php');
	require_once(PWD . 'app/Router.php');
	require_once(PWD . 'app/Model.php');


	#########
	# MODEL #
	#########
	//On va appeler un modèle, est l'initialiser
	$db = new Model($bdd);

	###########
	# ROUTAGE #
	###########
	//Partie gérant l'appel des controlleurs
	$router = new Router($_SERVER['REQUEST_URI']);
	$router->loadRoute($router->getRoute());

