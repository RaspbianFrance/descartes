<?php
namespace controllers\publics;
class Index extends \Controller
{
	//Pour ajouter du cache, ajouter un attribut :
	//public $cacheNomMethode = durée_cache_en_minute;

	//Pour ajouter un cache custom, ajouter une methode :
	//public function _cacheCustomNomMethod() { return $yourCustomTraitements }
	//Pour ajouter un header sur le cache, ajouter une methode :
	//public function _cacheHeaderNomMethod() { return header("Content-type: text/plain") }

	/**
	 * Page d'accueil du site
	 */	
	public function home()
	{
		return $this->render("index/home");
	}

	/**
	 * Page qui affiche une ou deux valeures passées via les paramètres
	 * @param string $firstValue : La première valeur à afficher
	 * @param string $secondValue : La seconde valeur à afficher. Optionnelle
	 */
	public function showValue($firstValue, $secondValue = false)
	{
		return $this->render('index/showValue', [$firstValue, $secondValue]);
	}
}
