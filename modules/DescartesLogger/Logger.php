<?php

namespace DescartesLogger;

/**
 * Cette classe est la principale classe de DescartesLogger, elle reprend en grande partie le logger PSR-3
 */
class Logger extends Psr\Log\AbstractLogger
{
	/**
	 * Cette méthode permet de logger une information avec le niveau de notre choix (une constante de Levels).
	 * Par ailleurs, cette méthode prend en charge les templates type {clef} qu'il map avec les données de $context
	 * @param string $level : Une constante de Levels, défini le niveau de logg
	 * @param string $message : Le message à logger
	 * @param array $context : Optionnel, le tableau des données à mapper au message (une donnée est fourni au format 'clef' => 'valeur'
	 * @return int : 0 = erreur, 1 = Log, 2 = Log et email
	 */
	public function log($level, $message, array $context = array())
	{
		//On map d'éventuels flags du message avec les données du context
		foreach ($context as $key => $value)
		{
			$message = str_replace('{' . $key . '}', $value, $message);
		}

		//On ajoute l'en-tête au log et le saut de ligne
		$logHeader = '[' . date('Y-m-d H:i:s') . '] [PID : ' . getmypid() . ' USER UID : ' . posix_geteuid() . '] (IP : ' . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN') . ') [' . $level . '] : ';
		$message = $logHeader . $message . "\n";

		//Si le niveau d'erreur n'existe pas
		if (!array_key_exists($level, Levels::getLevels()))
		{
			return 0;
		}

		//On va essayer d'utiliser des constantes pour les différentes infos du logger.
		//Si on ne trouve pas on va définir une valeur par défaut
		$activ = DESCARTESLOGGER_ACTIV;
		$minLevel = DESCARTESLOGGER_MIN_LEVEL;
		$email = DESCARTESLOGGER_EMAIL;
		$minLevelEmail = DESCARTESLOGGER_MIN_LEVEL_EMAIL;
		$file = DESCARTESLOGGER_FILE;

		if (!$activ)
		{
			return 0;
		}

		//Ne pas oublier que les levels sont etiquetés selon une forme étrange (plus important = 0 moins important = 7)
		if ($minLevel > Levels::getLevels()[$level])
		{
			return 0;
		}

		//On commence à essayer d'ecrire
		if (!$handle = fopen($file, 'ab'))
		{
			return 0;
		}

		if (!fwrite($handle, $message))
		{
			return 0;
		}

		fclose($handle);

		//Si on ne doit pas mail
		if (!$email || !($minLevelEmail > Levels::getLevels()[$level]))
		{
			return 1;
		}

		//On essaye de mail	
		if (!mail($email, 'DescartesLogger - ' . $level, $message))
		{
			return 1;
		}

		return 2;
	}
}
