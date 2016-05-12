<?php

namespace modules\DescartesLogger\internals;

/**
 * Cette classe est la principale classe de DescartesLogger, elle reprend en grande partie le logger PSR-3
 */
class Levels extends Psr\Log\LogLevel
{
	//Level log from RFC 5424 syslog Protocol
	public static function getLevels()
	{
		return array(
			self::EMERGENCY => 7,
			self::ALERT => 6,
			self::CRITICAL => 5,
			self::ERROR => 4,
			self::WARNING => 3,
			self::NOTICE => 2,
			self::INFO => 1,
			self::DEBUG => 0,
		);
	}
}
