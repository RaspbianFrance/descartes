<?php
	class internalIncs extends Controller
	{
		/**
		 * Cette fonction retourne le header html
		 * @param string $title : Le titre de la page, si non fourni laissé à vide
		 */
		public function head($title = '')
		{
			$title = (!empty($title) ? $title . ' - ' : '') . WEBSITE_TITLE;

			return $this->render('incs/head', array(
				'title'			=> $title,
				'description'		=> WEBSITE_DESCRIPTION,
				'keywords'		=> WEBSITE_KEYWORDS,
				'author'		=> WEBSITE_AUTHOR,
			));
		}
		
		/**
		 * Cette fonction retourne le header du site
		 * @param string $actual_page, le nom de la page actuel, il s'agit de la clef renseignée dans le tableau $page défini dans cette méthode
		 */
		public function header($actual_page)
		{
			$pages = array(
				'Admin'		=> $this->generateUrl('admin'),
			);
			
			
			return $this->render('incs/header', array(
				'pages'			=> $pages,
				'actual_page'		=> $actual_page,
			));
		}

		/**
		 * Cette fonction retourne le header de l'admin du site
		 * @param string $currentPage, le nom de la page actuel, il s'agit de la clef renseignée dans le tableau $page défini dans cette méthode
		 */
		public function headerAdmin($currentPage)
		{
			global $db;

			$tables = $db->getAllTables();
			
			return $this->render('incs/headerAdmin', array(
					'tables' => $tables,
					'currentPage' => $currentPage,
			));
		}

		/**
		 * Cette fonction retourne le footer du site
		 */	
		public function footer()
		{			
			return $this->render('incs/footer');
		}
		
		/**
		 * Cette fonction retourne la pagination d'une page
		 * @param array $pagination : Le tableau qui contient les pages à afficher. Sous les clefs 'current' le numéro de la page en cours, sous 'prev' la page précedente, sous 'next' la suivante
		 */
		public function paginate($pagination)
		{
			return $this->render('incs/pagination', array('pagination' => $pagination));
		}

		/**
		 * Cette fonction gère les alertes
		 * @param $_SESSION['alert'] : Une alerte à afficher au format ['type' => 'texte']. Le type peut être "success", "danger", "warning", "info"
		 */
		public function alert ()
		{
			if (!isset($_SESSION['alert']))
			{
				return null;
			}

			$alert = $_SESSION['alert'];
			unset($_SESSION['alert']);

			return $this->render('incs/alert', ['alert' => $alert]);
		}
	}
