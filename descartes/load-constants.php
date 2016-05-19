<?php
	//On va inclure les constantes fixes
	require_once(__DIR__ . '/constants.php');

	//0n va inclure les constantes customisable
	require_once(PWD . '/constants.php');

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
	}
