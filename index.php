<?php
	session_start();

	//On creé le csrf token si il n'existe pas
	if (!isset($_SESSION['csrf']))
	{
		$_SESSION['csrf'] = str_shuffle(uniqid().uniqid());
	}

	##############
	# INCLUSIONS #
	##############
	//On va inclure l'ensemble des fichiers necessaires
	require_once(__DIR__ . '/mvc/constants.php');
	require_once(PWD . 'mvc/autoload.php');
	require_once(PWD . 'vendor/autoload.php');
	require_once(PWD . 'mvc/conn_bdd.php');
	require_once(PWD . 'mvc/secho.php');
	require_once(PWD . 'mvc/Controller.php');
	require_once(PWD . 'mvc/ApiController.php');
	require_once(PWD . 'mvc/Router.php');
	require_once(PWD . 'mvc/Model.php');


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

