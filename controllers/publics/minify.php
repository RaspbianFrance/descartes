<?php
	namespace controllers\publics;
	use \modules\DescartesMinify\internals\Minifier as Minifier;

	/**
	 * page de minification et combine des fichiers css et javascript, utilisant DescartesMinify
	 */
	class minify extends Controller
	{
		//Cache de la minification CSS et JS, 30j
		public $cacheCss = DESCARTESMINIFY_CACHE_CSS_DURATION;
		public $cacheJs = DESCARTESMINIFY_CACHE_JS_DURATION;

		//Cache custom, permet de tj utiliser le cache
		public function _cacheCustomCss() { return 'always_use_me_css'; }
		public function _cacheCustomJs() { return 'always_use_me_js'; }

		//Cache custom des headers, permet de jouer les bons headers avant d'appeler le cache (en l'occurence css et js)
		public function _cacheHeaderCss() { return header("Content-type: text/css"); }
		public function _cacheHeaderJs() { return header("Content-type: application/javascript"); }

		/**
		 * Cette fonction retourne la minification de tous les fichiers css indiqué dans le tableau $filesToMinify
		 * On utilise un header css adapté
		 * @return void
		 */
		public function css ()
		{
			//On liste les fichiers à minifier dans l'ordre ou ils doivent re combiné
			$filesToMinify = array(
				HTTP_PWD_CSS . 'bootstrap.min.css',
				HTTP_PWD_CSS . 'bootstrap-theme.min.css',
				'http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700',
				HTTP_PWD_CSS . 'font-awesome.min.css',
				HTTP_PWD_CSS . 'bootstrap-slider.css',
				HTTP_PWD_CSS . 'responsive-font.min.css',
				HTTP_PWD_CSS . 'style.css',
			);

			//On minify
			$minifier = new Minifier();
			$minifiedText = $minifier->minifyCss($filesToMinify);
		
			//On retourne le header adapté et on affiche le texte minifié
			header("Content-type: text/css");
			echo $minifiedText;
		}

		/**
		 * Cette fonction retourne la minification de tous les fichiers js indiqué dans le tableau $filesToMinify
		 * On utilise un header js adapté
		 * @return void
		 */
		public function js ()
		{
			//On liste les fichiers à minifier dans l'ordre ou ils doivent re combiné
			$filesToMinify = array(
				HTTP_PWD_JS . 'jquery-2.1.1.min.js',
				HTTP_PWD_JS . 'bootstrap.min.js',
			);

			//On minify
			$minifier = new Minifier();
			$minifiedText = $minifier->minifyJavascript($filesToMinify);
		
			//On retourne le header adapté et on affiche le texte minifié
			header("Content-type: application/javascript");
			echo $minifiedText;
		}
	}
