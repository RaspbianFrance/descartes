<?php
	namespace modules\DescartesAdministrator\publics;

	/**
	 * Ce controller gère l'ensemble des fonctions de l'administration
	 */
	class Admin extends \Controller
	{
		/**
		 * Cette fonction empeche de se connecter à toute page de l'admin si on est pas préalablement connecté
		 */	
		public function _before()
		{
			if (empty($_SESSION['connect']) || !$_SESSION['connect'])
			{
				header('Location: ' . $this->generateUrl('DescartesAdministratorLogin', 'login'));
				die();
			}
		}

		/**
		 * Cette fonction retourne la page d'index de l'admin
		 * @return void;
		 */	
		public function index()
		{
			return $this->render('DescartesAdministrator/Admin/index');
		}

		/**
		 * Cette fonction retourne la page d'ajout dans la table de notre choix
		 * @param string $table : Nom de la table dans laquel on veux insérer une donnée
		 * @param int $success : Le nombre de lignes ajoutées, par défaut à null, sert à afficher un message
		 */
		public function add($table, $nbLine = false)
		{
			global $bdd;
			$model = new \Model($bdd);
			$fields = $model->describeTable($table);

			//Si on a pas de champs a analyser
			if (!$fields)
			{
				return header('Location: ' . $this->generateUrl('DescartesAdministratorAdmin', 'index'));
			}

			$fieldsToShow = [];

			foreach ($fields as $name => $field)
			{
				$fieldToShow = [];

				//On elimine les auto_increments
				if ($field['AUTO_INCREMENT'])
				{
					continue;
				}

				$fieldToShow['name'] = $name;
				$fieldToShow['size'] = $field['SIZE'];
				$fieldToShow['has_default'] = $field['HAS_DEFAULT'];
				$fieldToShow['null'] = $field['NULL'];
				$fieldToShow['type'] = 'text';
				$fieldToShow['textarea'] = false;
				$fieldToShow['pattern'] = false;
				$fieldToShow['foreign'] = false;
				$fieldToShow['possibleValues'] = [];
				$fieldToShow['required'] = false;
				
				//On va gérer la qualification du type ou du pattern
				switch ($field['TYPE'])
				{
					case 'INT' :
						$fieldToShow['type'] = 'number';
						break;
					case 'DATE' :
						$fieldToShow['pattern'] = '[0-9]{4}-[0-9]{2}-[0-9]{2}';
						break;
					case 'DATETIME' :
						$fieldToShow['pattern'] = '[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}';
				}

				if ($field['TYPE'] == 'VARCHAR' && $field['SIZE'] > 255)
				{
					$fieldToShow['textarea'] = true;
				}

				if ($field['FOREIGN'])
				{
					$fieldToShow['foreign'] = true;
					foreach ($model->getPossibleValuesForForeign($table, $field['NAME']) as $possibleValue)
					{
						$fieldToShow['possibleValues'][] = $possibleValue['possible_value'];
					}
				}

				if (!$fieldToShow['null'] && !$fieldToShow['has_default'])
				{
					$fieldToShow['required'] = true;
				}

				$fieldsToShow[] = $fieldToShow;
			}

			return $this->render('DescartesAdministrator/Admin/add', array(
				'fieldsToShow' => $fieldsToShow,
				'table' => $table,
				'nbLine' => $nbLine,
			));
		}

		/**
		 * Cette fonction retourne la page de modication d'une ligne donnée dans la table de notre choix
		 * @param string $table : Nom de la table dans laquel on veux insérer une donnée
		 * @param string $primary : La valeur du champs primary de la ligne à modifier
		 * @param int $nbLine : Le nombre de ligne modifiée, par défaut à false, sert à afficher un message
		 */
		public function edit($table, $primary, $nbLine = false)
		{
			global $bdd;
			$model = new \Model($bdd);

			if (!$primaryField = $model->getPrimaryField($table))
			{
				echo 'Cette table n\'a pas de clef primaire';
				return false;
			}

			if (!$lignes = $model->getFromTableWhere($table, [$primaryField => $primary]))
			{
				return header('Location: ' . $this->generateUrl('DescartesAdministratorAdmin', 'index'));
			}
			$ligne = $lignes[0];

			$fields = $model->describeTable($table);

			//Si on a pas de champs a analyser
			if (!$fields)
			{
				return header('Location: ' . $this->generateUrl('DescartesAdministratorAdmin', 'index'));
			}

			$fieldsToShow = [];

			foreach ($fields as $name => $field)
			{
				$fieldToShow = [];

				//On elimine les auto_increments
				if ($field['AUTO_INCREMENT'])
				{
					continue;
				}

				$fieldToShow['name'] = $name;
				$fieldToShow['size'] = $field['SIZE'];
				$fieldToShow['has_default'] = $field['HAS_DEFAULT'];
				$fieldToShow['null'] = $field['NULL'];
				$fieldToShow['type'] = 'text';
				$fieldToShow['textarea'] = false;
				$fieldToShow['pattern'] = false;
				$fieldToShow['foreign'] = false;
				$fieldToShow['possibleValues'] = [];
				$fieldToShow['required'] = false;
				
				//On va gérer la qualification du type ou du pattern
				switch ($field['TYPE'])
				{
					case 'INT' :
						$fieldToShow['type'] = 'number';
						break;
					case 'DATE' :
						$fieldToShow['pattern'] = '[0-9]{4}-[0-9]{2}-[0-9]{2}';
						break;
					case 'DATETIME' :
						$fieldToShow['pattern'] = '[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}';
				}

				if ($field['TYPE'] == 'VARCHAR' && $field['SIZE'] > 255)
				{
					$fieldToShow['textarea'] = true;
				}

				if ($field['FOREIGN'])
				{
					$fieldToShow['foreign'] = true;
					foreach ($model->getPossibleValuesForForeign($table, $field['NAME']) as $possibleValue)
					{
						$fieldToShow['possibleValues'][] = $possibleValue['possible_value'];
					}
				}

				if (!$fieldToShow['null'] && !$fieldToShow['has_default'])
				{
					$fieldToShow['required'] = true;
				}

				$fieldsToShow[] = $fieldToShow;
			}

			return $this->render('DescartesAdministrator/Admin/edit', array(
				'fieldsToShow' => $fieldsToShow,
				'table' => $table,
				'ligne' => $ligne,
				'nbLine' => $nbLine,
				'primary' => $primary,
			));
		}

		/**
		 * Cette fonction permet d'insérer des données dans une table
		 * @param string $table : Le nom de la table dans laquelle on veux insérer une donnée
		 * @param string $csrf : Le jeton CSRF pour vérifier la validité de la requete
		 * @param array $_POST : Toutes les données POST sont insérées si possible
		 */
		public function create($table, $csrf)
		{
			global $bdd;

			if (!$this->verifyCSRF($csrf))
			{
				return header('Location: ' . $this->generateUrl('DescartesAdministratorAdmin', 'add', array('table' => $table, 'nbLine' => 0)));
			}

			$model = new \Model($bdd);
			$nbLineInsert = $model->insertIntoTable($table, $_POST);

			//On renvoie sur la page d'ajout
			return header('Location: ' . $this->generateUrl('DescartesAdministratorAdmin', 'add', array('table' => $table, 'nbLine' => $nbLineInsert)));
		}

		/**
		 * Cette fonction modifi les données d'une ligne d'une table
		 * @param string $table : Le nom de la table à modifier
		 * @param string $primary : La valeur du champ primary de la ligne qu'on veux modifier
		 * @param string $csrf : Le CSRF token
		 * @param array $_POST : Le tableau $_POST est utilisé comme source des nouvelles données
		 */
		public function modify($table, $primary, $csrf)
		{
			//On verifie que le CSRF est ok	
			if (!$this->verifyCSRF($csrf))
			{
				return header('Location: ' . $this->generateUrl('DescartesAdministratorAdmin', 'index'));
			}

			global $bdd;
			$model = new \Model($bdd);

			if (!$primaryField = $model->getPrimaryField($table))
			{
				echo 'Cette table n\'a pas de clef primaire';
				return false;
			}

			$result = $model->updateTableWhere($table, $_POST, [$primaryField => $primary]);
	
			//On renvoie sur la page d'ajout
			return header('Location: ' . $this->generateUrl('DescartesAdministratorAdmin', 'edit', array('table' => $table, 'primary' => $primary, 'nbLine' => $result)));
		}

		/**
		 * Cette fonction permet d'afficher les données d'une table
		 * @param string $table : Le nom de la table afficher
		 * @param mixed $orderByField : Le numero ou le nom du champs par lequel on veux trier (par défaut 1)
		 * @param int $orderDesc : Si on doit trier par ordre ascendant ou descendant (par défaut ascendant = 0)
		 * @param int $page : Non obligatoire, par défaut 1, le numéro de page à afficher (on sort 25 resultat par page)
		 */
		public function showTable($table, $orderByField = 1, $orderDesc = 0, $page = 1)
		{
			global $bdd;
			$model = new \Model($bdd);

			//On s'assure que la page est minimum à 1, et entier
			$page = $page < 1 ? 1 : (int)$page;

			//On gère l'initialisation via la session
			$_SESSION['admin-show-table'] = array(
				'table' => $table,
				'orderByField' => $orderByField,
				'orderDesc' => $orderDesc,
				'page' => $page,
			);
			
			$fields = $model->describeTable($table);
			
			//On renvoie sur l'accueil si ya pas de clef primaire
			if (!$primary = $model->getPrimaryField($table))
			{
				return header('Location: ' . $this->generateUrl('DescartesAdministratorAdmin', 'index'));
			}


			$lignes = $model->getFromTableWhere($table, null, $orderByField, $orderDesc, 25, 25 * ($page - 1)); #On get sans conditions en paginant

			//On prépare les données de la pagination
			$totalLignes = $model->countTable($table);

			$paramsArray = [
				'table' => $table,
				'orderByField' => $orderByField,
				'orderDesc' => $orderDesc
			];

			$pagination = \modules\DescartesPagination\internals\Pagination::generate($totalLignes, 25, $page, 'page', $this, 'showTable', $paramsArray);

			$this->render('DescartesAdministrator/Admin/showTable', array(
				'table' => $table,
				'orderByField' => $orderByField,
				'orderDesc' => $orderDesc,
				'page' => $page,
				'fields' => $fields,
				'primary' => $primary,
				'lignes' => $lignes,
				'pagination' => $pagination,
			));
		}

		/**
		 * Cette page retourne la page de confirmation des suppression
		 * @param string $table : Le nom de la table a modifier
		 * @param string $primary : La valeure du champs primaire de la table
		 */
		public function deleteLine($table, $primary)
		{
			global $bdd;
			$model = new \Model($bdd);

			return $this->render('DescartesAdministrator/Admin/deleteLine', array(
				'table' => $table,
				'primary' => $primary
			));
		}

		/**
		 * Cette page supprime la ligne de la base
		 * @param string $table : Le nom de la table a modifier
		 * @param string $primary : La valeure du champs primaire de la ligne
		 * @param string $csrf : Le csrf, necessaire pour assurer la suppression
		 */
		public function destroyLine($table, $primary, $csrf)
		{
			//On verifie que le CSRF est ok	
			if (!$this->verifyCSRF($csrf))
			{
				return header('Location: ' . $this->generateUrl('DescartesAdministratorAdmin', 'index'));
			}
			
			global $bdd;
			$model = new \Model($bdd);

			//On renvoie sur l'accueil si ya pas de clef primaire
			if (!$primaryField = $model->getPrimaryField($table))
			{
				return header('Location: ' . $this->generateUrl('DescartesAdministratorAdmin', 'index'));
			}

			$nbDelete = $model->deleteFromTableWhere($table, [$primaryField => $primary]);

			return header('Location: ' . $this->generateUrl($this, 'showTable', isset($_SESSION['admin-show-table']) ? $_SESSION['admin-show-table'] : ['table' => $table]));
		}
	}
