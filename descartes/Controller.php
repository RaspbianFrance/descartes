<?php
	/**
	 * Cette classe sert de mère à tous les controlleurs, elle permet de gérer l'ensemble des fonction necessaires à l'affichage de template, à l'écriture de logs, etc.
	 */
	class Controller
	{
		protected $id; //Id unique du controller actuel
		protected $callDate; //Date ou l'on a appelé ce controller
		protected $userIp; //Adresse Ip de l'utilisateur qui demande l'appel de ce controller

		/**
		 * Le constructeur du Controller
		 */
		public function __construct()
		{
			$this->id = uniqid(); //On défini un id unique pour ce controller
			$this->callDate = (new DateTime())->format('Y-m-d H:i:s');
			$this->userIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
		}

		/**
		 * Cette fonction permet d'afficher un template
		 * @param string $template : Nom du template à afficher
		 * @param array $variables : Tableau des variables à passer au script, au format 'nom' => valeur (par défaut array())
		 * @return mixed, Faux si le fichier n'existe pas, les variables passées sinon
		 */
		protected function render($template, $variables = array())
		{
			foreach($variables as $clef => $value)
			{
				$$clef = $value;
			}

			$templatePath = PWD_TEMPLATES . '/' . $template . '.php';

			if(file_exists($templatePath))
			{
				require($templatePath);
				unset($templatePath);
				return $variables;
			}

			return false;
		}

		/**
		 * Cette fonction permet de générer une adresse URL interne au site
		 * @param mixed $controller : Un objet controller ou le nom du controleur à employer (par défaut vide)
		 * @param string $method : Nom de la méthode à employer (par défaut vide)
		 * @param string $params : Tableau des paramètres à passer à la fonction, sous forme de tableau, sans clefs
		 * @param string $getParams : Tableau des paramètres $_GET à employer au format 'nom' => valeur (par défaut array())
		 * @return string, Url générée
		 */ 
		public static function generateUrl($controller, $method, $params = array(), $getParams = array())
		{
			global $descartesRoutes;

			if ($controller instanceof Controller)
			{
				$controller = explode('\\', get_class($controller));
				$controller = end($controller);
			}

			$url = HTTP_PWD;

			if (!array_key_exists($controller, $descartesRoutes) || !array_key_exists($method, $descartesRoutes[$controller]))
			{
				return false;
			}

			//On calcul les paramètres get
			$paramsToJoins = array();
			foreach ($getParams as $clef => $valeur)
			{
				$paramsToJoins[] = $clef . '=' . rawurlencode($valeur);
			}
			$getParamsString = count($getParams) ? '?' . implode('&', $paramsToJoins) : '';

			if (!is_array($descartesRoutes[$controller][$method]))
			{
				$descartesRoutes[$controller][$method] = [$descartesRoutes[$controller][$method]];
			}

			foreach ($descartesRoutes[$controller][$method] as $route)
			{
				$flags = [];
				preg_match_all('#\\\{(.+)\\\}#iU', preg_quote($route, '#'), $flags);
				$flags = $flags[1];

				//Si pas le meme nombre d'arguments
				if (count($flags) != count($params))
				{
					continue;
				}

				//Si un argument n'est pas dispo
				foreach ($flags as $flag)
				{
					if (!array_key_exists($flag, $params))
					{
						continue 2;
					}

					//Si l'argument est dispo, on le remplace dans la route
					$route = str_replace('{' . $flag . '}', rawurlencode($params[$flag]), $route);
				}

				return $url . $route . $getParamsString;
			}

			return false;
		}

		/**
		 * Cette fonction permet d'afficher un texte de façon sécurisée.
		 *
		 * @param string $text : Le texte à afficher
		 * @param boolean $nl2br (= true) : Si vrai, on transforme le "\n" en <br/>.
		 * @param boolean $escapeQuotes (= true) : Si vrai, on transforme les ' et " en équivalent html
		 * @param boolean $echo (= true) : Si vrai, on affiche directement la chaine modifiée. Sinon, on la retourne sans l'afficher.
		 *
		 * @return mixed : Si $echo est vrai, void. Sinon, la chaine modifiée
		 */
		public static function s($text, $nl2br = false, $escapeQuotes = true, $echo = true)
		{
			$text = $escapeQuotes ? htmlspecialchars($text, ENT_QUOTES) : htmlspecialchars($text, ENT_NOQUOTES);
			$text = $nl2br ? nl2br($text) : $text;
			if (!$echo)
			{
				return $text;
			}
			echo $text;
		}

		/**
		 * Cette fonction vérifie si un argument csrf a été fourni et est valide
		 * @param string $csrf : argument optionel, qui est la chaine csrf à vérifier. Si non fournie, la fonction cherchera à utiliser $_GET['csrf'] puis $_POST['csrf'].
		 * @return boolean : True si le csrf est valide. False sinon.
		 */
		public static function verifyCSRF($csrf = '')
		{
			if (!FROM_WEB)
			{
				return true;
			}

			if (!$csrf)
			{
				$csrf = isset($_GET['csrf']) ? $_GET['csrf'] : $csrf;
				$csrf = isset($_POST['csrf']) ? $_POST['csrf'] : $csrf;
			}

			if ($csrf == $_SESSION['csrf'])
			{
				return true;
			}
			
			return false;
		}
	} 
