<?php
	/**
	 * Cette classe gère l'appel des ressources
	 */
	class Router
	{
		private $callUrl; //L'URL appelée, depuis la racine du framework
		private $routes; //Les routes du site

		/**
		 * Constructeur du router
		 * @param string $url : L'url appelée, tel que retournée par $_SERVER['REQUEST_URI']
		 * @param string $routes : Les routes du site
		 */
		public function __construct ($url, $routes)
		{
			//On va débarasser l'url des parties inutiles pour conserver uniquement l'adresse depuis la racine du framework et sans les ancres et paramètres
			$this->setCallUrl($this->filterUrl($url));
			$this->setRoutes($routes);
		}

		public function setCallUrl ($url)
		{
			$this->callUrl = $url;
		}

		public function getCallUrl ()
		{
			return $this->callUrl;
		}

		public function setRoutes ($routes)
		{
			$this->routes = $routes;
		}

		public function getRoutes ()
		{
			return $this->routes;
		}

		/**
		 * Cette méthode nettoie une url pour en conserver seulement la partie qui suit la racine du framework et sans les ancres et parametres
		 * @param string $url : L'url à nettoyer
		 * @return string : L'url nettoyée
		 */
		public function filterUrl ($url)
		{
			$partToRemove = strstr(preg_replace('#http(s)?://#', '', HTTP_PWD), '/'); //On calcul la partie à ignorer de l'url
			$url = mb_strcut($url, mb_strlen($partToRemove)); //On retire la partie à ignorer de l'url
			$url = explode('?', $url)[0]; //on ne garde que ce qui précède les arguments
			$url = explode('#', $url)[0]; //On ne garde que ce qui précède les ancres
			return $url;
		}

		/**
		 * Cette méthode retourne la page 404 par défaut
		 */
		public function return404 ()
		{
			http_response_code(404);
			include(PWD . '/descartes/404.php');
			die();
		}

		/**
		 * Transforme le tableau des routes en un tableau exploitable
		 * @param array &$urls : Le tableau à remplir avec les routes générées
		 * @param array $routes : Le tableau des routes originel
		 * @param string $methodToCallLabel : Le label correspondant à la méthode à appeler. Par défaut vide, il sera automatiquement rempli petit à petit.
		 * @return none : La fonction ne retourne rien mais rempli le tableau passé via $urls, sous la forme ['methode à appeler' => 'route correspondante]
		 */
		public function generateRoutes (&$urls, $routes, $label = '')
		{
			foreach ($routes as $key => $value)
			{
				$newLabel = $label ? $label . '::' . $key : $key;

				if (is_array($value))
				{
					$this->generateRoutes($urls, $value, $newLabel);
					continue;
				}

				$urls[$newLabel] = $value;
			}
		}


		/**
		 * Trouve le label de la méthode correspondant à une URL
		 * @param array $generatedRoutes : Le tableau des routes exploitable tel que retourné par generateRoutes
		 * @param string $url : L'url à tester
		 * @return mixed : Le label de la méthode à appeler ou false si il n'y a pas de méthode pour cette URL
		 */
		public function getMethodToCallLabelForUrl ($generatedRoutes, $url)
		{
			foreach ($generatedRoutes as $methodToCallLabel => $generatedRoute)
			{
				//On transforme la route en expression régulière et on la compare avec l'url
				$routePattern = preg_replace('#\\\{(.+)\\\}#iU', '([^/]+)', preg_quote($generatedRoute, '#'));
				$routePattern = preg_replace('#/$#', '/?', $routePattern);

				//Si l'url match pas, on passe à la suivante
				if (!preg_match('#^' . $routePattern . '$#U', $url))
				{
					continue;
				}

				return $methodToCallLabel;
			}

			return false;
		}

		/**
		 * Extrait d'une URL les valeurs des paramètres à transmettre à une méthode pour une route donnée
		 * @param string $url : L'URL dont on veux extraire les données
		 * @param array $generatedRoutes : Le tableau des routes exploitable tel que retourné par generateRoutes
		 * @param string $methodToCallLabel : La méthode à appeler sous la forme retournée par getMethodToCallLabelForUrl
		 * @return mixed : False en cas d'erreur, sinon un tableau des valeurs au format ["label" => "valeur"]
		 */
		public function getParamsForMethodToCallLabel ($url, $generatedRoutes, $methodToCallLabel)
		{
			if (!isset($generatedRoutes[$methodToCallLabel]))
			{
				return false;
			}

			$route = $generatedRoutes[$methodToCallLabel];

			//On récupère les flags de la route
			$flags = [];
			preg_match_all('#\\\{(.+)\\\}#iU', preg_quote($route, '#'), $flags);
			$flags = $flags[1];

			//On transforme la route en expression régulière utilisable pour récupérer les valeurs
			$routePattern = preg_replace('#\\\{(.+)\\\}#iU', '([^/]+)', preg_quote($route, '#'));
			$routePattern = preg_replace('#/$#', '/?', $routePattern);

			//On récupère les valeurs des flags
			$values = [];
			if (!preg_match('#^' . $routePattern . '$#U', $url, $values))
			{
				return false;
			}
			unset($values[0]);

			$values = array_map('rawurldecode', $values);

			//On retourne les valeurs associées aux flags
			return array_combine($flags, $values);
		}

		/**
		 * Cette fonction retourne la méthode à appeler depuis sa forme texte
		 * @param string $methodToCallLabel : Le label de la méthode à appeler
		 * @return $mixed : False si le label est invalide. Sinon, la méthode à appeler sous la forme d'un tableau ['controller' => 'controllerName', 'method' => 'methodName']
		 */
		public function getMethodeToCallFormMethodeToCallLabel ($methodToCallLabel)
		{
			$methodToCallLabel = explode('::', $methodToCallLabel);

			if (!isset($methodToCallLabel[0], $methodToCallLabel[1]))
			{
				return false;
			}	

			$controllerName = '\controllers\publics\\' . $methodToCallLabel[0];
			$controller = new $controllerName();

			$methodName = $methodToCallLabel[1];

			if ($controller instanceof ApiController)
			{
				//On va choisir le type à employer
				$httpMethod = $_SERVER['REQUEST_METHOD'];
				switch (mb_convert_case($httpMethod, MB_CASE_LOWER))
				{
					case 'delete' :
						$prefixMethod = 'delete';
						break;
					case 'patch' :
						$prefixMethod = 'patch';
						break;
					case 'post' :
						$prefixMethod = 'post';
						break;
					case 'put' :
						$prefixMethod = 'put';
						break;
					default :
						$prefixMethod = 'get';
				}
				$methodName = $prefixMethod . mb_convert_case($method, MB_CASE_TITLE);	
			}

			return array(
				'controller' => $controllerName,
				'method' => $methodName,
			);	
		}

		/**
		 * Vérifie si une méthode est bien appelable, spécialement depuis le web
		 * @param array $methodToCall : La méthode à appelée, tel que retournée par getMethodeToCallFormMethodeToCallLabel
		 * @return boolean : true si appelable, false sinon
		 */
		public function checkIsCallableFromWeb ($methodToCall)
		{
			//Si on ne peux pas créer le controller
			if (!$controller = new $methodToCall['controller']())
			{
				return false;
			}

			//Si la méthode à appeler n'existe pas, ou commence par '_' (privée), on retourne une 404
			if (!is_callable([$controller, $methodToCall['method']]) || mb_substr($methodToCall['method'], 0, 1) == '_')
			{
				return false;
			}

			return true;
		}

		/**
		 * Vérifie si une méthode reçois bien tout les paramètres nécessaires
		 * @param array $methodToCall : la méthode à appelée, tel que retournée par getMethodeToCallFormMethodeToCallLabel
		 * @param array $params : Les paramètres à passer à la méthode
		 * @return mixed : Si tout est bon, un tableau contenant tous les paramètres, dans l'ordre de passage à la fonction. Si il manque un paramètre obligatoire, retourne false
		 */
		public function checkParametersValidityForMethodeToCall ($methodToCall, $params)
		{
			//On construit la liste des arguments de la méthode, dans l'ordre
			$reflection = new ReflectionMethod($methodToCall['controller'], $methodToCall['method']);
			$methodArguments = [];

			foreach ($reflection->getParameters() as $parameter)
			{
				//Si le paramètre n'est pas fourni et n'as pas de valeur par défaut
				if (!array_key_exists($parameter->getName(), $params) && !$parameter->isDefaultValueAvailable())
				{
					return false;
				}

				//Si on a une valeur par défaut dispo, on initialise la variable avec
				if ($parameter->isDefaultValueAvailable())
				{
					$methodArguments[$parameter->getName()] = $parameter->getDefaultValue();
				}

				//Si la variable n'existe pas, on passe
				if (!array_key_exists($parameter->getName(), $params))
				{
					continue;
				}

				//On ajoute la variable dans le tableau des arguments de la méthode	
				$methodArguments[$parameter->getName()] = $params[$parameter->getName()];
			}

			return $methodArguments;
		}

		/**
		 * Cette fonction permet de vérifier si le cache est activé pour une methode, et si oui quel fichier utiliser
		 * @param array $methodToCall : Le controller et la méthode à appelée, tel que retourné par getMethodeToCallFormMethodeToCallLabel
		 * @param array $params : Les paramètres à passer à la méthode, tel que retourné par getParamsForMethodToCallLabel
		 * @return mixed : Si pas de cache, faux. Sinon un tableau avec deux clefs, "state" => statut du nom de fichier retourné (true, le fichier doit être utilisé, false, le fichier doit être créé), "file" => Le chemin du fcihier de cache
		 */
		public function checkForCache ($methodToCall, $params)
		{
			//Si on ne doit pas activer le cache
			if (!ACTIVATING_CACHE)
			{
				return false;
			}

			$controllerName = $methodToCall['controller'];
			$methodName = $methodToCall['method'];
			$titledMethodName = mb_convert_case($methodName, MB_CASE_TITLE);

			$controller = new $controllerName();

			//Si on na pas de cache pour ce fichier
			if (!property_exists($controller, 'cache' . $titledMethodName))
			{
				return false;
			}

			//Si on a du cache, on va déterminer le nom approprié
			//Format de nom = <hash:nom_router.nom_methode><hash_datas>
			$hashName = md5($controllerName . $methodName);
			
			//Par défaut pour le hash data on utilise les infos GET, POST et les params
			$hashDatas = md5(json_encode($_GET) . json_encode($_POST) . json_encode($params));

			//On va gérer le cas d'un cache custom
			if (method_exists($controller, '_cacheCustom' . $titledMethodName))
			{
				$cacheCustomName = '_cacheCustom' . $titledMethodName;
				$hashDatas = md5($controller->$cacheCustomName());
			}

			//On va gérer des headers custom sur le fichier de cache en appelant si elle existe la fonction des headers
			if (method_exists($controller, '_cacheHeader' . $titledMethodName))
			{
				$cacheHeadersCustomName = '_cacheHeader' . $titledMethodName;
				$controller->$cacheHeadersCustomName();
			}

			$filePath = PWD_CACHE . '/' . $hashName . $hashDatas;

			//Si il n'existe pas de fichier de cache pour ce fichier
			if (!file_exists($filePath))
			{
				return array('state' => false, 'file' => $filePath);
			}

			//Sinon, si le fichier de cache existe
			$fileLastChange = filemtime($filePath);

			//On calcul la date de mise à jour valide la plus ancienne possible
			$now = new DateTime();
			$propertyName = 'cache' . $titledMethodName;
			$propertyValue = $controller->$propertyName;
			$now->sub(new DateInterval('PT' . $propertyValue . 'M'));
			$maxDate = $now->format('U');
			
			//Si le fichier de cache est trop vieux
			if ($fileLastChange < $maxDate)
			{
				return array('state' => false, 'file' => $filePath);
			}

			//Sinon, on retourne le fichier de cache en disant de l'utiliser
			return array('state' => true, 'file' => $filePath);
		}

		/**
		 * Appel le routeur et la méthode correspondant à une URL
		 * @param string $url : L'url à analyser
		 * @param string $routes : Les routes du site
		 */
		public function callRouterForUrl ($url, $routes)
		{
			$generatedRoutes = [];
			$this->generateRoutes($generatedRoutes, $routes);

			if (!$methodToCallLabel = $this->getMethodToCallLabelForUrl($generatedRoutes, $url))
			{
				return $this->return404();
			}

			if (!$methodToCall = $this->getMethodeToCallFormMethodeToCallLabel($methodToCallLabel))
			{
				return $this->return404();
			}

			if (!$this->checkIsCallableFromWeb($methodToCall))
			{
				return $this->return404();
			}

			$params = $this->getParamsForMethodToCallLabel($url, $generatedRoutes, $methodToCallLabel);
			if ($params === false)
			{
				return $this->return404();
			}

			$params = $this->checkParametersValidityForMethodeToCall($methodToCall, $params);
			if ($params === false)
			{
				return $this->return404();
			}

			$checkForCache = $this->checkForCache($methodToCall, $params);

			//Si on ne doit pas utiliser de cache
                        if ($checkForCache === false)
			{
				$controller = new $methodToCall['controller']();
				call_user_func_array([$controller, $methodToCall['method']], $params);
                                return null;
                        }

                        //Si on doit utiliser un cache avec un nouveau fichier
                        if ($checkForCache['state'] == false)
                        {
                                //On créer le fichier avec le contenu adapté
                                ob_start();
				call_user_func_array([$controller, $method], $params);
                                $content = ob_get_contents();
                                file_put_contents($checkForCache['file'], $content);
                                ob_end_clean();
                        }

                        //On utilise le fichier de cache
                        readfile($checkForCache['file']);
			return null;
		}
	} 
