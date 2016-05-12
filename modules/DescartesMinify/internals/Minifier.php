<?php
	namespace modules\DescartesMinify\internals;

	/**
	 * Cette classe permet de minifier un ensemble de fichiers de façon à optimiser les requetes HTTP vers un site
	 * La classe doit-être utilisée dans une page qui couplée au système de cache pour être proprement efficace et devrait utiliser un cache "custom", lequel devrait être constitué d'une chaine fixe !
	 * 
	 * ATTENTION : Cette classe emploi le service http://cssminifier.com/ pour css et http://javascript-minifier.com/ pour le javascript. Nous tenons à rapidement expliquer ce choix
	 * Suite à nos recherches nous n'avons trouvé aucun système de minification en PHP qui soit à la fois simple à mettre en oeuvre et à intégrer au sein d'un module tout en restant compréhensible.
	 * La pluspart de ces outils tendent à devenir trp complexes car ils intègres trop de fonctionnalités, ce qui est tout simplement mauvais.
	 * Quitte à employé un service qui offre un code incompréhensible, nous préférons utiliser un service qui ne nous fourni pas son code mais qui ne nous demandera aucune maintenance et qui pourras facilement être remplacé par un équivalent en cas de besoin !
	 */

	class Minifier
	{
		/**
		 * Cette fonction permet de minifier des fichiers javascript et de les concaténer en un seul fichier
		 * @param array $files : Les fichiers à minifier sous forme d'un tableau. Les fichiers seront concaténés dans l'ordre du tableau. Ce tableau doit contenir le chemin de chaque fichier, eventuellement avec un son protocole réseau
		 * @return mixed : Le résultat de cette concaténation/minification sous forme d'une chaine de caractère.
		 */
		public function minifyJavascript ($files)
		{
			$url = 'http://javascript-minifier.com/raw';
			$paramName = 'input';
			$textToMinify = $this->combineFiles($files);

			return $this->callMinifyService($url, $paramName, $textToMinify);
		}

		/**
		 * Cette fonction permet de minifier des fichiers css et de les concaténer en un seul fichier
		 * @param array $files : Les fichiers à minifier sous forme d'un tableau. Les fichiers seront concaténés dans l'ordre du tableau. Ce tableau doit contenir le chemin de chaque fichier, eventuellement avec un son protocole réseau
		 * @return string : Le résultat de cette concaténation/minification sous forme d'une chaine de caractère.
		 */
		public function minifyCss ($files)
		{
			$url = 'http://cssminifier.com/raw';
			$paramName = 'input';
			$textToMinify = $this->combineFiles($files);

			return $this->callMinifyService($url, $paramName, $textToMinify);
		}

		/**
		 * Cette fonction permet de mettre bout à bout tous les fichiers
		 * @param string $files : Le fichiers à mettre bout à bout
		 * @return mixed : Les fichiers concaténés sous forme de chaine de caractère si tout est bon, faux sinon.
		 */
		private function combineFiles ($files)
		{
			$text = '';

			foreach ($files as $file)
			{
				if (!$fileContent = file_get_contents($file))
				{
					continue;
				}

				$text .= $fileContent . "\n";
			}

			return $text;
		}

		/**
		 * Cette fonction permet d'appeler un service de minification en lui spécifiant
		 * @param string $url : L'adresse du service de minification à appeler
		 * @param string $paramName : Le nom du paramètre POST à utiliser pour le texte à minifier
		 * @param string $textToMinify : Le texte à minifier
		 */
		private function callMinifyService ($url, $paramName, $textToMinify)
		{
			$postdata = array(
				'http' => array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query([$paramName => $textToMinify]),
				)
			);

			return file_get_contents($url, false, stream_context_create($postdata));
		}
	}
