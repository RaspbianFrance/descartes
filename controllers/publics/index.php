<?php
namespace publics\controllers;
class index extends \Controller
{
	//Pour ajouter du cache, ajouter un attribut :
	//public $cache_nomMethode = durée_cache_en_minute;

	//Pour ajouter un cache custom, ajouter une methode :
	//public function _cache_custom_nomMethod() { return $yourCustomTraitements }

	/**
	 * Page d'index par défaut
	 */	
	public function byDefault()
	{
		return $this->render("index/default");
	}
}
