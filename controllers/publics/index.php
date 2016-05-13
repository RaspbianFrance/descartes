<?php
namespace publics\controllers;
class index extends \Controller
{
	//Pour ajouter du cache, ajouter un attribut :
	//public $cacheNomMethode = durée_cache_en_minute;

	//Pour ajouter un cache custom, ajouter une methode :
	//public function _cacheCustomNomMethod() { return $yourCustomTraitements }
	//Pour ajouter un header sur le cache, ajouter une methode :
	//public function _cacheHeaderNomMethod() { return header("Content-type: text/plain") }

	/**
	 * Page d'index par défaut
	 */	
	public function byDefault()
	{
		return $this->render("index/default");
	}

	/**
	 * Page d'une méthode
	 */
	public function method()
	{
		return $this->render('index/method');
	}

	/**
	 * Page d'une méthode avec un paramètre
	 */
	public function methodWithParam($param)
	{
		return $this->render('index/methodWithParam', ['param' => $param]);
	}
}
