<?php
	/**
	 * Cette page gère les appels de la console
	 */
	class internalConsole extends Controller
	{

		/**
		 * Cette fonction retourne l'aide de la console
		 */
		public function help()
		{
			//On défini les commandes dispo
			$commands = array(
				'generateObjectFromTable' => array(
					'description' => 'Cette commande permet de générer un objet correspondant à la table fournie en argument.',
					'requireds' => array(
						'-t' => 'Nom de la table pour laquelle on veux générer un objet',
					),
					'optionals' => array(),
				),
			);

			$message  = "Vous êtes ici dans l'aide de la console.\n";
			$message .= "Voici la liste des commandes disponibles : \n";

			//On écrit les texte pour la liste des commandes dispos
			foreach ($commands as $name => $value)
			{
				$requireds = isset($value['requireds']) ? $value['requireds'] : array();
				$optionals = isset($value['optionals']) ? $value['optionals'] : array();

				$message .= '	' . $name . ' : ' . $value['description'] . "\n";
				$message .= "		Arguments obligatoires : \n";
				if (!count($requireds))
				{
					$message .= "			Pas d'arguments\n";
				}
				else
				{
					foreach ($requireds as $argument => $desc)
					{
						$message .= '				- ' . $argument . ' : ' . $desc . "\n";
					}
				}

				$message .= "		Arguments optionels : \n";
				
				if (!count($optionals))
				{
					$message .= "			Pas d'arguments\n";
				}
				else
				{
					foreach ($optionals as $argument => $desc)
					{
						$message .= '				- ' . $argument . ' : ' . $desc . "\n";
					}
				}
			}

			echo $message;
		}

		/**
		 * Cette fonction génère un objet PHP complet depuis un nom de table, et affiche cet objet
		 * @return empty : 
		 */
		public function generateObjectFromTable()
		{
			$options = getopt('c:t:');

			global $db;
			$tableName = $options['t'];
			$columns = $db->getColumnsForTable($tableName);
			$columns = explode(', ', $columns);
			
			echo "L'objet " . $tableName . " va être généré : 
-------------------------------------------------------
";

			echo "
/**
 * Cette classe représente un objet tel que décrit par la table " . $tableName . " de la base de données
 */
class " . ucfirst($tableName) . "
{
";
	
			//Pour chaque colonne on écrit une propriétée
			foreach ($columns as $column)
			{
				echo '        public $' . $column . ";\n";
			}

			//On écrit le constructeur, qui se construit soit à vide (objet non rempli), soit en passant un tableau avec pour chaque paramètre à remplir le nom du paramètre et sa valeur
			echo "
	/**
	 * Constructeur de l'objet " . $tableName . "
	 * @param array \$objectArray : Ce paramètre est optionnel. Il s'agit du tableau des paramètres à initialiser, avec en clef le nom du paramètre, et en valeur la valeur à lui donner
	 * @return void
	 */
	public function __construct(\$objectArray = array())
	{
		foreach(\$objectArray as \$name => \$value)
		{
			if (property_exists(\$this, \$name))
			{
				\$this->\$name = \$value;
			}
		}
	}\n";	

			echo "
	/**
	 * Cette fonction permet de transformer cet objet en tableau
	 * @return array : L'objet sous forme de tableau
	 */
	public function toArray()
	{
		return get_object_vars(\$this);
	}\n\n";

			foreach ($columns as $column)
			{
				echo "
	public function get" . ucfirst($column) . '()' . "
	{
		return \$this->" . $column . ";
	}
	";
				echo "
	public function set" . ucfirst($column) . '($value)' . "
	{
		\$this->" . $column . " = \$value;
	}
	";
			}
		echo "
}

---------------------------------------------------------
Génération de l'objet " . $tableName . " terminée.";
	
		}

	}
