<?php
	/**
	 * Cette fonction permet d'analyser une route pour appeler le controller adapté, avec la méthode voulue, et de transformer les parametres données dans l'url en $_GET
	 * @param string $route : Route à analyser
	 */
	
	function load_route($route)
	{
		//On récupére la route, et on l'éclate par les '/', et les '?'
		$route = preg_split('#[/?]#', $route);

		foreach($route as $key => $val)
		{
			if(empty($val))
			{
				unset($route[$key]);
			}
		}

		$route = array_values($route);

		//On lie le bon controlleur
		if (empty($route[0]) || !file_exists(PWD_CONTROLLER . $route[0] . '.php')) //Si on a pas de premier parametre, ou qu'il ne correspond à aucun controlleur
		{
			$controllerPath = PWD_CONTROLLER . DEFAULT_CONTROLLER . '.php'; //On prend le controlleur par défaut
			$controllerName = DEFAULT_CONTROLLER; //On défini le nom du controlleur par défaut
		}
		else //Sinon, si tout est bon
		{
			$controllerPath = PWD_CONTROLLER . $route[0] . '.php'; //On défini le controlleur
			$controllerName = $route[0]; //On défini le nom du controlleur
		}
		//On insert le bon controlleur, et on l'instancie (on le fait maintenant pour vérifier la présence de la méthode à appeler pour ce controlleur)
		require_once($controllerPath);
		$controller = new $controllerName;
		
		//On lie la bonne méthode
		if (empty($route[1]) || !method_exists($controller, $route[1])) //Si on a pas de second parametre, ou qu'il ne correspond à aucune méthode du controlleur
		{
			$method = DEFAULT_METHOD; //On prend la méthode par défaut
		}
		else //Sinon, si tout est bon
		{
			$method = $route[1]; //On défini la méthode appelée
		}
		//On transforme les paramètres $_GET passés par l'url au format clef_value. Ex : prenom_pierre-lin = $_GET['prenom'] => 'pierre-lin'
		if (count($route) > 2) //Si on a plus de deux paramètres qui ont été passé
		{
			unset($route[0], $route[1]); //On supprime le controlleur, et la route, des paramètres, il ne reste que les parametres a passer en GET
			foreach($route as $param) //On passe sur chaque paramètre a transformer en GET
			{
				$param = explode('_', $param, 2); //On récupère le paramètre, via la délimiteur '_', en s'arretant au premier
				$_GET[urldecode($param[0])] = (isset($param[1])) ? urldecode($param[1]) : NULL; //On définit la propriétée GET correspondante
			}
		}
		
		//On appel la méthode récupérée quelques lignes plus tôt
		$controller->$method();
	}	
