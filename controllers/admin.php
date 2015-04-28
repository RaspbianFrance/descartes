<?php
	/**
	 * Ce controller gère l'ensemble des fonctions de l'administration
	 */
	class admin extends Controller
	{
		/**
		 * Cette fonction empeche de se connecter à toute page de l'admin si on est pas préalablement connecté
		 */	
		public function before()
		{
			if (empty($_SESSION['connect']) || !$_SESSION['connect'])
			{
				header('Location: ' . $this->generateUrl("login","index"));
			}
		}

		/**
		 * Cette fonction est un alias de index
		 * @return void;
		 */	
		public function byDefault()
		{
			$this->index();
		}

		/**
		 * Cette fonction retourne la page d'index de l'admin
		 */
		public function index()
		{
			global $db;

			return $this->render('adminIndex');
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

			return $this->render('adminAdd', array(
				'fields' => $fields,
				'table' => $table,
				'success' => $success,
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

			return $this->render('adminEdit', array(
				'fields' => $fields,
				'table' => $table,
				'ligne' => $ligne,
				'success' => $success,
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

			//On renvoie sur la page d'ajout
			return header('Location: ' . $this->generateUrl('admin', 'add', array($table, $result)));
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
			if (!internalTools::verifyCSRF($csrf))
			{
				return header('Location: ' . $this->generateUrl('admin'));
			}

			global $db;
			$result = $db->updateTableWhere($table, $_POST, ['id' => $id]);

			//On renvoie sur la page d'ajout
			return header('Location: ' . $this->generateUrl('admin', 'edit', array($table, $id, $result)));
		}

		/**
		 * Cette fonction permet d'afficher les données d'une table
		 * @param string $table : Le nom de la table afficher
		 * @param int $page : Non obligatoire, par défaut 1, le numéro de page à afficher (on sort 25 resultat par page)
		 * @param int $nbDelete : Non obligatoire, par défaut null, le nombre de ligne supprimé s'il y a lieu
		 */
		public function liste($table, $page = 1, $nbDelete = null)
		{
			global $db;

			//On s'assure que la page est minimum à 1, et entier
			$page = $page < 1 ? 1 : (int)$page;

			$fields = $db->describeTable($table);

			$lignes = $db->getAll($table, '', false, 25, 25 * ($page - 1));

			//On renvoie sur l'accueil si ya pas de ligne (ca se presente par exemple sur des tables qui n'existes pas
			if (!$lignes)
			{
				return header('Location: ' . $this->generateUrl('admin'));
			}

			//Je prépare les données de la pagination
			$totalLignes = $db->countTable($table);
			$pagination = internalTools::generatePagination($totalLignes, 25, $page, $this->generateUrl('admin', 'liste', array($table)));

			$this->render('adminListe', array(
				'fields' => $fields,
				'lignes' => $lignes,
				'pagination' => $pagination,
				'table' => $table,
				'nbDelete' => $nbDelete,
			));
		}

		/**
		 * Cette page retourne la page de confirmation des suppression
		 * @param string $table : Le nom de la table a modifier
		 * @param string $id : L'id de la ligne a supprimer
		 */
		public function deleteLigne($table = '', $id = '')
		{
			return $this->render('adminDelete', array(
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
			if (!internalTools::verifyCSRF($csrf))
			{
				return header('Location: ' . $this->generateUrl('admin'));
			}
			
			global $db;
			$nbDelete = $db->deleteFromTableWhere($table, ['id' => $id]);
			return header('Location: ' . $this->generateUrl('admin', 'liste', array($table, 1, $nbDelete)));
		}
	}
