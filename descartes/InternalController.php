<?php
	/**
     * Cette classe sert de mère à tous les controlleurs internes
	 */
	class InternalController extends Controller
	{
		/**
         * Cette fonction construit la classe, elle prend en paramètre optionnel une connection PDO à une base de données
		 */
		public function __construct ($bdd = false)
		{
            $this->bdd = $bdd;

			//On construit aussi le controller traditionnel
			parent::__construct();
		}
	} 
