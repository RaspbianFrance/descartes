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
	require_once(__DIR__ . '/descartes/load-constants.php');
	require_once(PWD . '/descartes/autoload.php');
	require_once(PWD . '/vendor/autoload.php');
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
	$router = new Router($_SERVER['REQUEST_URI'], $descartesRoutes);
	$router->callRouterForUrl($router->getCallUrl(), $router->getRoutes());

