<?php
namespace controllers\internals;

class Incs extends \Controller
{
	/**
	 * Head html
	 */	
	public static function head (string $title)
	{
		return self::render("incs/head", ['title' => $title]);
    }


    /**
     * Footer html
     */
    public static function footer ()
    {
		return self::render("incs/footer");
    }

}
