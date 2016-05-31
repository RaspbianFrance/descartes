<?php
	//On va inclure les constantes fixes
	require_once(__DIR__ . '/environment.php');

	//0n va inclure les constantes customisable
	require_once(PWD . '/environment.php');

	//On va définir les constantes customisable
	foreach ($environment[ENVIRONMENT] as $name => $value)
	{
		define(mb_strtoupper($name), $value);
	}

	//On va inclure les constantes de tous les modules (dans l'ordre du dossier)
	foreach (scandir(PWD_MODULES) as $filename)
	{
		if ($filename == '.' || $filename == '..')
		{
			continue;
		}
		
		if (!file_exists(PWD_MODULES . '/' . $filename . '/constants.php'))
		{
			continue;
		}

		require_once(PWD_MODULES . '/' . $filename . '/constants.php');
		foreach ($environment[ENVIRONMENT] as $name => $value)
		{
			define(mb_strtoupper($name), $value);
		}
	}
