<?php
/**
 * page de login
*/
class login extends Controller
{
	public $cache_byDefault = 1;
	public $cache_show = 1;

	
	public function before()
	{
		if (!empty($_SESSION['connect']) && !$_SESSION['connect'])
		{
			header('Location: ' . $this->generateUrl("admin","index"));
		}
	}
	
	/**
	 * Cette fonction est un alias de show
	 * @return void;
	 */
	public function byDefault()
	{
		$this->index();
	}
	
	public function index()
	{
		if (!empty($_POST['login']) && !empty($_POST['password']))
		{
			$pseudo = $_POST['login'];
			$password = $_POST['password'];
			
			if ($pseudo == ADMIN_LOGIN && $password == ADMIN_PASSWORD)
			{
				$_SESSION['connect'] = true;
				header('Location: ' . $this->generateUrl("admin","index"));
				return true;
			}
			
			return $this->render("loginIndex", array(
				"message" => "Erreur lors de la connexion"
			));
		}
	
		$this->render("loginIndex");	
	}
	
	public function logout()
	{
		session_unset();
		session_destroy();
		$this->index();
	}
	
}
