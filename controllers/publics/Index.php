<?php
namespace controllers\publics;
class Index extends \Controller
{
	//Pour ajouter du cache, ajouter un attribut :
	//public $cacheHome = durée_en_minutes;

	//Pour ajouter un cache custom, ajouter une methode :
	//public function _cacheCustomHome() { return 'v1'; }

	//Pour ajouter un header sur le cache, ajouter une methode :
	//public function _cacheHeaderNomMethod() { return header("Content-type: text/plain") }

	/**
	 * Page d'accueil du site
	 */	
	public function home()
	{
		return $this->render("Index/home");
	}

	/**
	 * Page qui affiche une ou deux valeures passées via les paramètres
	 * @param string $firstValue : La première valeur à afficher
	 * @param string $secondValue : La seconde valeur à afficher. Optionnelle
	 */
	public function showValue($firstValue, $secondValue = false)
	{
		return $this->render('Index/showValue', ['firstValue' => $firstValue, 'secondValue' => $secondValue]);
	}
}
