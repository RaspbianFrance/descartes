#!/usr/bin/php
<?php
	/**
	 *	Cette page gère les scripts appelés en ligne de commande
	 */

	##############
	# INCLUSIONS #
	##############
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
	$controller = new internalConsole();

	$options = getopt('c:');

	if (!isset($options['c'])) //Si on a pas reçu de methode à appeler
	{
		echo "Vous devez précisez un script à appeler (-c 'nom du script').\n";
		echo "Pour plus d'infos, utilisez -c 'help'\n";
		exit(1); //Sorti avec erreur
	}
	
	if (!method_exists($controller, $options['c'])) //Si la méthode reçue est incorrect
	{
		echo "Vous avez appelé un script incorrect.\n";
		echo "Pour plus d'infos, utilisez -c 'help'\n";
		exit(2); //Sorti avec erreur
	}

	$controller->$options['c'](); //On appel la fonction

