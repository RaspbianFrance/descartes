<?php
	namespace modules\DescartesAdministrator\publics;

	/**
	 * Ce controller gère l'ensemble des fonctions de l'administration
	 */
	class admin extends \Controller
	{
		/**
		 * Cette fonction empeche de se connecter à toute page de l'admin si on est pas préalablement connecté
		 */	
		public function before()
		{
			if (empty($_SESSION['connect']) || !$_SESSION['connect'])
			{
				header('Location: ' . $this->generateUrl("login"));
			}
		}

		/**
		 * Cette fonction retourne le header de l'admin du site
		 * @param string $currentPage, le nom de la page actuel, il s'agit de la clef renseignée dans le tableau $page défini dans cette méthode
		 */
		protected function headerAdmin($currentPage = '')
		{
			global $db;

			$tables = $db->getAllTables();
			
			return $this->render('DescartesAdministrator/admin/header', array(
					'tables' => $tables,
					'currentPage' => $currentPage,
			));
		}

		/**
		 * Cette fonction retourne la page d'index de l'admin
		 * @return void;
		 */	
		public function byDefault()
		{
			global $db;
			return $this->render('DescartesAdministrator/admin/default');
		}

		/**
		 * Cette fonction retourne la page d'ajout dans la table de notre choix
		 * @param string $table : Nom de la table dans laquel on veux insérer une donnée
		 * @param int $success : Le nombre de ligne ajoutée, par défaut à null, sert à afficher un message
		 */
		public function add($table, $success = null)
		{
			global $db;
			$fields = $db->describeTable($table);

			//Si on a pas de champs a analyser
			if (!$fields)
			{
				return header('Location: ' . $this->generateUrl('admin'));
			}

			return $this->render('DescartesAdministrator/admin/add', array(
				'fields' => $fields,
				'table' => $table,
				'type' => 'success', 'text' => $success,
			));
		}

		/**
		 * Cette fonction retourne la page de modication d'une ligne donnée dans la table de notre choix
		 * @param string $table : Nom de la table dans laquel on veux insérer une donnée
		 * @param string $id : Id de la ligne à donner
		 * @param int $success : Le nombre de ligne modifiée, par défaut à null, sert à afficher un message
		 */
		public function edit($table, $id, $success = null)
		{
			global $db;
			$fields = $db->describeTable($table);
			$lignes = $db->getFromTableWhere($table, ['id' => $id]);

			//Si on a pas de champs a analyser
			if (!$fields || !$lignes)
			{
				return header('Location: ' . $this->generateUrl('admin'));
			}

			$ligne = $lignes[0];

			return $this->render('DescartesAdministrator/admin/edit', array(
				'fields' => $fields,
				'table' => $table,
				'ligne' => $ligne,
				'type' => 'success', 'text' => $success,
				'id' => $id,
			));
		}

		/**
		 * Cette fonction permet d'insérer des données dans une table
		 * @param string $table : Le nom de la table dans laquelle on veux insérer une donnée
		 * @param array $_POST : Toutes les données POST sont insérées si possible
		 */
		public function create($table)
		{
			global $db;
			$result = $db->insertIntoTable($table, $_POST);

			if (!$result)
			{
				$_SESSION['alert'] = ['type' => 'danger', 'text' => 'Impossible d\'ajouter la ligne'];
			}
			else
			{
				$_SESSION['alert'] = ['type' => 'success', 'text' => 'La ligne a bien été ajoutée'];
			}

			//On renvoie sur la page d'ajout
			return header('Location: ' . $this->generateUrl('admin', 'add', array($table)));
		}

		/**
		 * Cette fonction modifi les données d'une ligne d'une table
		 * @param string $table : Le nom de la table à modifier
		 * @param string $id : L'id de la donnée à modifier
		 * @param string $csrf : Le CSRF token
		 * @param array $_POST : Le tableau $_POST est utilisé comme source des nouvelles données
		 */
		public function modify($table, $id, $csrf)
		{
			//On verifie que le CSRF est ok	
			if (!\internalTools::verifyCSRF($csrf))
			{
				return header('Location: ' . $this->generateUrl('admin'));
			}

			global $db;
			$result = $db->updateTableWhere($table, $_POST, ['id' => $id]);
	
			if (!$result)
			{
				$_SESSION['alert'] = ['type' => 'danger', 'text' => 'Impossible de modifier la ligne'];
			}
			else
			{
				$_SESSION['alert'] = ['type' => 'success', 'text' => 'La ligne a bien été modifiée'];
			}

			//On renvoie sur la page d'ajout
			return header('Location: ' . $this->generateUrl('admin', 'edit', array($table, $id)));
		}

		/**
		 * Cette fonction permet d'afficher les données d'une table
		 * @param string $table : Le nom de la table afficher
		 * @param mixed $orderBy : Le numero ou le nom du champs par lequel on veux trier (par défaut 1)
		 * @param int $orderDesc : Si on doit trier par ordre ascendant ou descendant (par défaut ascendant = 0)
		 * @param int $page : Non obligatoire, par défaut 1, le numéro de page à afficher (on sort 25 resultat par page)
		 * @param int $nbDelete : Non obligatoire, par défaut null, le nombre de ligne supprimé s'il y a lieu
		 */
		public function liste($table, $orderBy = 1, $orderDesc = 0, $page = 1)
		{
			global $db;

			//On s'assure que la page est minimum à 1, et entier
			$page = $page < 1 ? 1 : (int)$page;

			//On gère l'initialisation via la session
			$_SESSION['admin-liste-' . $table] = array(
				'orderBy' => $orderBy,
				'orderDesc' => $orderDesc,
				'page' => $page,
			);
			
			$fields = $db->describeTable($table);

			$lignes = $db->getFromTableWhere($table, null, $orderBy, $orderDesc, 25, 25 * ($page - 1)); #On get sans conditions en paginant

			//On renvoie sur l'accueil si ya pas de ligne (ca se presente par exemple sur des tables qui n'existes pas
			if (!$lignes)
			{
				return header('Location: ' . $this->generateUrl('admin'));
			}

			//Je prépare les données de la pagination
			$totalLignes = $db->countTable($table);
			$pagination = \internalTools::generatePagination($totalLignes, 25, $page, $this->generateUrl('admin', 'liste', array($table, $orderBy, $orderDesc)));

			$this->render('DescartesAdministrator/admin/liste', array(
				'fields' => $fields,
				'lignes' => $lignes,
				'pagination' => $pagination,
				'table' => $table,
			));
		}

		/**
		 * Cette page retourne la page de confirmation des suppression
		 * @param string $table : Le nom de la table a modifier
		 * @param string $id : L'id de la ligne a supprimer
		 */
		public function deleteLigne($table = '', $id = '')
		{
			return $this->render('DescartesAdministrator/admin/delete', array(
				'table' => $table,
				'id' => $id
			));
		}

		/**
		 * Cette page supprime la ligne de la base
		 * @param string $table : Le nom de la table a modifier
		 * @param string $id : L'id de la ligne à supprimer
		 * @param string $csrf : Le csrf, necessaire pour assurer la suppression
		 */
		public function confirmDelete($table, $id, $csrf)
		{
			//On verifie que le CSRF est ok	
			if (!\internalTools::verifyCSRF($csrf))
			{
				return header('Location: ' . $this->generateUrl('admin'));
			}
			
			global $db;
			$nbDelete = $db->deleteFromTableWhere($table, ['id' => $id]);

			if (!$nbDelete)
			{
				$_SESSION['alert'] = ['type' => 'danger', 'text' => 'Impossible de supprimer la ligne'];
			}
			else
			{
				$_SESSION['alert'] = ['type' => 'success', 'text' => 'La ligne a bien été supprimée'];
			}

			$arguments = ['table' => $table];

			if(isset($_SESSION['admin-liste-' . $table]['orderBy']))
			{
				$arguments['orderBy'] = $_SESSION['admin-liste-' . $table]['orderBy'];
			}	

			if(isset($_SESSION['admin-liste-' . $table]['orderDesc']))
			{
				$arguments['orderDesc'] = $_SESSION['admin-liste-' . $table]['orderDesc'];
			}	
			
			if(isset($_SESSION['admin-liste-' . $table]['page']))
			{
				$arguments['page'] = $_SESSION['admin-liste-' . $table]['page'];
			}	

			return header('Location: ' . $this->generateUrl('admin', 'liste', $arguments));
		}
	}
