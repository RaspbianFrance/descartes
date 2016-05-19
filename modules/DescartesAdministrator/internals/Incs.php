<?php
	namespace modules\DescartesAdministrator\internals;
	/**
	 * Cette classe retournes des éléments HTML régulièrements utilisés
	 */
	class Incs extends \Controller
	{
		/**
		 * Retourne le head html du module
		 */
		public function htmlHead()
		{
			self::render('DescartesAdministrator/Incs/htmlHead');
		}

		/**
		 * Retourne le menu de l'admin
		 * @param string : L'identifiant de la page courrante
		 */
		public function htmlNav($currentPage = '')
		{
			global $bdd;
			$model = new \Model($bdd);

			$tables = $model->getAllTables();
			return self::render('DescartesAdministrator/Incs/htmlNav', [
				'tables' => $tables,
				'currentPage' => $currentPage,
			]);
		}

		/**
		 * Retourne le footer de l'admin
		 */
		public function htmlFooter ()
		{
			return $this->render('DescartesAdministrator/Incs/htmlFooter');
		}

		/**
		 * Retourne la pagination de l'admin
		 */
		public function htmlPaginate ($pagination)
		{
			return $this->render('DescartesAdministrator/Incs/htmlPaginate', ['pagination' => $pagination]);
		}
	}
