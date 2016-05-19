<?php
	namespace modules\DescartesPagination\internals;

	class Pagination
	{
		/**
		 * Cette fonction génère des données de pagination
		 * @param int $nbResults : Nombre de résultat récupérés
		 * @param int $nbByPage : Le nombre de resultats à afficher par page
		 * @param int $page : Le numéro de page courant
		 * @param string $pageNumberLabel : Le nom de la variable qui défini le numéro de page
		 * @param mixed $controller : Le nom ou une instance du controller pour lequel on veux la pagination
		 * @param string $method : Le nom de la méthode pour laquelle on veux la pagination
		 * @param array $params : Le tableau des paramètres de la méthode pour laquelle on veux la pagination
		 * @param array $getParams : Optionnel, les arguments $_GET à passer à la requete
		 * @return array : Un tableau de pagination qui contient les infos vers la première page, la dernière, le nom de la page courante et, jusqu'à 3 page avant et 3 pages après
		 */
		public static function generate($nbResults, $nbByPage, $page, $pageNumberLabel, $controller, $method, $params, $getParams = [])
		{
			//On s'assure que la page est minimum à 1, et entier
			$page = $page < 1 ? 1 : (int)$page;

			$nbPage = ceil($nbResults / $nbByPage);

			$pagination = [];
			
			$params[$pageNumberLabel] = $page;
			$pagination['current'] = [
				'number' => $page,
				'url' => \Controller::generateUrl($controller, $method, $params, $getParams),
			];
	
			if ($page > 1)
			{
				$params[$pageNumberLabel] = 1;
				$pagination['first'] = [
					'number' => 1,
					'url' => \Controller::generateUrl($controller, $method, $params, $getParams),
				];
			}

			if ($page < $nbPage)
			{
				$params[$pageNumberLabel] = $nbPage;
				$pagination['last'] = [
					'number' => 1,
					'url' => \Controller::generateUrl($controller, $method, $params, $getParams),
				];
			}

			//On crée la pagination des pages avant et après celle en cours
			$pagination['before'] = array();
			$pagination['after'] = array();
			$i = 0;
			while ($i < 3)
			{
				$i ++;
				$pageBeforeNb = $page - $i;
				$pageAfterNb = $page + $i;

				if ($pageBeforeNb >= 1)
				{
					$params[$pageNumberLabel] = $pageBeforeNb;
					$pagination['before'][] = [
						'number' => $pageBeforeNb,
						'url' => \Controller::generateUrl($controller, $method, $params, $getParams),
					];
				}

				if ($pageAfterNb <= $nbPage)
				{
					$params[$pageNumberLabel] = $pageAfterNb;
					$pagination['after'][] = [
						'number' => $pageAfterNb,
						'url' => \Controller::generateUrl($controller, $method, $params, $getParams),
					];
				}
			}

			//On remet la pagination before dans le bon ordre pour l'affichage
			$pagination['before'] = array_reverse($pagination['before']);

			return $pagination;
		}
	}
