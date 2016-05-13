<?php
	/**
	 * Cette classe gère l'appel des ressources
	 */
	class Router
	{
		public $uri; //URI actuelle
		public $controllerName; //Nom du controleur obtenu depuis l'uri de création
		public $methodName; //Nom de la méthode obtenu depuis l'uri de création
		public $params; //Tableau des paramètres GET passé dans l'uri

		/**
		 * Constructeur du router
		 * @param string $uri : L'uri à l'URI appelée, tel que retournée par $_SERVER['REQUEST_URI']
		 */
		public function __construct($uri = '')
		{
			if ($uri)
			{
				$this->setUri($uri);
				$this->setControllerName($this->parseController($uri));
				$this->setMethodName($this->parseMethod($uri));
				$this->setParams($this->parseParams($uri));
			}
		}

		//Getters et setters
		public function getUri()
		{
			return $this->uri;
		}

		public function setUri($value)
		{
			$this->uri = $value;
		}

		public function getControllerName()
		{
			return $this->controllerName;
		}

		public function setControllerName($value)
		{
			$this->controllerName = $value;
		}

		public function getMethodName()
		{
			return $this->methodName;
		}

		public function setMethodName($value)
		{
			$this->methodName = $value;
		}

		public function getParams()
		{
			return $this->params;
		}

		public function setParams($value)
		{
			$this->params = $value;
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
		 * Retourne l'uri parsée sous forme de tableau avec seulement les éléments clefs
		 * @param string $uri : L'uri à analyser, tel que retournée par $_SERVER['REQUEST_URI']
		 * @return array : Le tableau de l'uri parsée
		 */
		public function parseUri($uri)
		{
			//On va débarasser l'uri de l'adresse du site
			$directoryToRemove = strstr(preg_replace('#http(s)?://#', '', HTTP_PWD), '/'); 
			$uri = mb_strcut($uri, mb_strlen($directoryToRemove));

			//On retire les paramètres et on explose la chaine
			$uri = strstr($uri, '?', true);
			$uri = explode('/', $uri);

			//On garde seulement les repertoires non vides
			foreach($uri as $key => $val)
			{
				if(empty($val) && $val !== 0 && $val !== '0')
				{
					unset($uri[$key]);
				}
			}

			//On retourne le tableau en le ré-indexant à 0
			return array_values($uri);
		}

		/**
		 * Retrouve le controlleur dans un URI
		 * @param string $uri : l'uri à analyser
		 * @return string : Le nom du controleur avec son namespace adapté
		 */
		public function parseController($uri)
		{
			//On va analyser parser l'uri
			$uri = $this->parseUri($uri);

			//Si pas de controlleur, on prend celui par défaut
			if (empty($uri[0]))
			{
				return '\controllers\publics\\' . DEFAULT_CONTROLLER;
			}

			//Si le controlleur n'existe pas, on retourne une 404
			if (!file_exists(PWD_CONTROLLER . '/publics/' . $uri[0] . '.php'))
			{
				return $this->return404();
			}

			//On retourne le controlleur
			return '\controllers\publics\\' . $uri[0];
		}

		/**
		 * Retrouve la méthode dans un URI
		 * @param string $uri : l'uri à analyser
		 * @return string : Le nom de la methode
		 */
		public function parseMethod($uri)
		{
			//On instancie le controlleur
			$controllerName = $this->parseController($uri);
			$controller = new $controllerName();

			//On parse l'uri et on récupère les paramères dans l'URI pour plus tardrecupère les paramètres dans l'url pour les utiliser un peu plus tard
			$uri = $this->parseUri($uri);
			$params = $this->parseParams($uri);

			//On détermine quelle uri appeler (si non définie, celle par défaut)
			$method = empty($uri[1]) ? DEFAULT_METHOD : $uri[1];

			//Si le controlleur est un controlleur API, on ajoute le préfixe adapté à la methode à appeler
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
				$method = $prefixMethod . mb_convert_case($method, MB_CASE_TITLE);
			}

			//Si la méthode à appeler n'existe pas, ou commence par '_' (privée), on retourne une 404
			if (!is_callable([$controller, $method]) || mb_substr($method, 0, 1) == '_')
			{
				return $this->return404();
			}

			//On instancie la classe reflection de PHP sur la méthode que l'on veux analyser pour l'objet controller
			$methodAnalyser = new ReflectionMethod($controller, $method);

			//Si la méthode à appeler demande plus de paramètres que fournis on retourne une 404
			if ($methodAnalyser->getNumberOfRequiredParameters() > count($params))
			{
				return $this->return404();
			}
	
			return $method;
		}

		/**
		 * Retrouve les paramètres dans un URL
		 * @param string $uri : l'uri à analyser
		 * @return array : La liste des paramètres au format $clef => $valeur
		 */
		public function parseParams($uri)
		{
			$uri = $this->parseUri($uri); //On récupère l'uri parsé
			$params = array();

			//On transforme les paramètres $_GET passés par l'url au format clef-value. Ex : prenom-pierre-lin = $_GET['prenom'] => 'pierre-lin'
			if (count($uri) > 2) //Si on a plus de deux paramètres qui ont été passé
			{
				unset($uri[0], $uri[1]); //On supprime le controlleur, et l'uri, des paramètres, il ne reste que les parametres a passer en GET
				foreach ($uri as $param)
				{
					$params[] = rawurldecode($param);
				}
			}
			return $params;
		}

		/**
		 * Cette fonction permet de vérifier si le cache est activé pour une uri, et si oui quel fichier utiliser
		 * @param string $uri : Uri à analyser
		 * @return mixed : Si pas de cache, faux. Sinon un tableau avec deux clefs, "state" => statut du nom de fichier retourné (true, le fichier doit être utilisé, false, le fichier doit être créé), "file" => Le nom du fcihier de cache
		 */
		public function verifyCache($uri)
		{
			//On récupère le nom du controller et de la méthode
			$controllerName = $this->parseController($uri);
			$methodName = $this->parseMethod($uri);
			$params = $this->parseParams($uri);

			$controller = new $controllerName();

			//Si on ne doit pas activer le cache ou si on na pas de cache pour ce fichier
			if (!ACTIVATING_CACHE || !property_exists($controller, 'cache_' . $methodName))
			{
				return false;
			}

			//Si on a du cache, on va déterminer le nom approprié
			//Format de nom = <hash:nom_router.nom_methode><hash_datas>
			$hashName = md5($controllerName . $methodName);
			
			//Par défaut pour le hash data on utilise les infos GET, POST et les params
			$hashDatas = md5(json_encode($_GET) . json_encode($_POST) . json_encode($params));

			//On va gérer le cas d'un cache custom
			if (method_exists($controller, '_cache_custom_' . $methodName))
			{
				$cacheCustomName = '_cache_custom_' . $methodName;
				$hashDatas = md5($controller->$cacheCustomName());
			}

			//On va gérer des headers custom sur le fichier de cache en appelant si elle existe la fonction des headers
			if (method_exists($controller, '_cache_custom_headers_' . $methodName))
			{
				$cacheHeadersCustomName = '_cache_custom_headers_' . $methodName;
				$controller->$cacheHeadersCustomName();
			}

			$fileName = $hashName . $hashDatas;

			//Si il n'existe pas de fichier de cache pour ce fichier
			if (!file_exists(PWD_CACHE . $fileName))
			{
				return array('state' => false, 'file' => $fileName);
			}

			//Sinon, si le fichier de cache existe
			$fileLastChange = filemtime(PWD_CACHE . $fileName);

			//On calcul la date de mise à jour valide la plus ancienne possible
			$now = new DateTime();
			$propertyName = 'cache_' . $methodName;
			$propertyValue = $controller->$propertyName;
			$now->sub(new DateInterval('PT' . $propertyValue . 'M'));
			$maxDate = $now->format('U');
			
			//Si le fichier de cache est trop vieux
			if ($fileLastChange < $maxDate)
			{
				return array('state' => false, 'file' => $fileName);
			}

			//Sinon, on retourne le fichier de cache en disant de l'utiliser
			return array('state' => true, 'file' => $fileName);
		}

		/**
		 * Cette fonction permet de charger la page adaptée a partir d'une url
		 * Elle gère également le cache
		 * @param string $uri : Uri à analyser pour charger une page
		 * @return void
		 */
		public function loadUri($uri)
		{
			$params = $this->parseParams($uri); //On récupère les paramètres à passer à la fonction
			$controllerName = $this->parseController($uri); //On récupère le nom du controleur à appeler
			$controller = new $controllerName(); //On créer le controleur

			if (method_exists($controller, '_before')) //Si une fonction before existe pour ce controller, on l'appel
			{
				$controller->_before(); //On appel la fonction before
			}

			$methodName = $this->parseMethod($uri); //On récupère le nom de la méthode
			$verifyCache = $this->verifyCache($uri); //Au passage en plus de vérifier le cache, on joue les headers utiles

			//Si on ne doit pas utiliser de cache
			if ($verifyCache === false)
			{
				call_user_func_array(array($controller, $methodName), $params); //On appel la methode, en lui passant les arguments necessaires
				return null;
			}

			//Si on doit utiliser un cache avec un nouveau fichier
			if ($verifyCache['state'] == false)
			{
				//On créer le fichier avec le contenu adapté
				ob_start();
				call_user_func_array(array($controller, $methodName), $params); //On appel la methode, en lui passant les arguments necessaires
				$content = ob_get_contents();
				file_put_contents(PWD_CACHE . $verifyCache['file'], $content);
				ob_end_clean();
			}

			//On utilise le fichier de cache
			readfile(PWD_CACHE . $verifyCache['file']);
		}
	} 
