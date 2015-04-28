<?php
/**
 * page d'index
 */
class index extends Controller
{
	//Pour ajouter du cache, ajouter un attribut :
	//public $cache_nomMethode = durée_cache_en_minute;

	/**
	 * Page d'index par défaut
	 */	
	public function byDefault()
	{
		return $this->render("index");
	}
}
