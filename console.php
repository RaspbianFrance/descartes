<?php
	###############
	# ENVIRONMENT #
	###############
	require_once(__DIR__ . '/descartes/load-environment.php');

	##############
	# INCLUDE #
	##############
    //Use autoload
	require_once(PWD . '/descartes/autoload.php');
	require_once(PWD . '/vendor/autoload.php');

	###########
	# ROUTING #
	###########
    require_once(PWD . '/routes.php'); //Include routes

    //Routing current query
    Console::execute_command($argv);

