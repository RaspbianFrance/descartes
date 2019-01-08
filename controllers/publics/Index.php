<?php
namespace controllers\publics;

class Index extends \Controller
{
	/**
	 * Home Page
	 */	
	public function home()
	{
		return $this->render("index/home");
	}

    /**
     * Page showing values, with some optionnal
     * @param string $first_value : First value to show
     * @param int $second_value : Second value to show
	 */
	public function show_value(string $first_value, ?int $second_value = null)
	{
		return $this->render('index/show-value', ['first_value' => $first_value, 'second_value' => $second_value]);
	}
}
