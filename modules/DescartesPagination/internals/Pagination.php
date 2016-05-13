<?php
	namespace modules\internals\DescartesPagination;

	class Pagination
	{
		/**
		 * Cette fonction génère des données de pagination
		 * @param int $nbResults : Nombre de résultat récupérés
		 * @param int $nbByPage : Le nombre de resultats à afficher par page
		 * @param int $page : Le numéro de page courant
		 * @param string $urlFixe : La partie fixe de l'url
		 * @return array : Un tableau de pagination comme voulu par le template pagination
		 */
		public static function generate($nbResults, $nbByPage, $page, $urlFixe)
		{
			$nbPage = ceil($nbResults / $nbByPage);

			$pagination = array('current' => $page);
	
			if ($page > 1)
			{
				$pagination['first'] = $urlFixe;
			}

			if ($page < $nbPage)
			{
				$pagination['last'] = $urlFixe . $nbPage;
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
					$pagination['before'][] = array(
						'url' => $urlFixe . $pageBeforeNb,
						'nb' => $pageBeforeNb,
					);
				}

				if ($pageAfterNb <= $nbPage)
				{
					$pagination['after'][] = array(
						'url' => $urlFixe . $pageAfterNb,
						'nb' => $pageAfterNb,
					);
				}
			}

			//On remet la pagination before dans le bon ordre pour l'affichage
			$pagination['before'] = array_reverse($pagination['before']);

			return $pagination;
		}
	}
