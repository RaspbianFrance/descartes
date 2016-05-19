<?php
	namespace modules\DescartesAdministrator\publics;

	/**
	 * page de login Admin
	 */
	class Login extends \Controller
	{
		public function login()
		{
			if (isset($_SESSION['connect']) && $_SESSION['connect'])
			{
				return header('Location: ' . $this->generateUrl("DescartesAdministratorAdmin", 'index'));
			}

			if (empty($_POST['login']) || empty($_POST['password']))
			{
				return $this->render("DescartesAdministrator/Login/login");
			}

			$pseudo = $_POST['login'];
			$password = $_POST['password'];
				
			if ($pseudo != DESCARTESADMINISTRATOR_LOGIN || $password != DESCARTESADMINISTRATOR_PASSWORD)
			{
				return $this->render("DescartesAdministrator/Login/login", array(
					"message" => "Erreur lors de la connexion"
				));
			}

			$_SESSION['connect'] = true;
			return header('Location: ' . $this->generateUrl("DescartesAdministratorAdmin", "index"));
		}
		
		public function logout()
		{
			unset($_SESSION['connect']);
			return header('Location: ' . $this->generateUrl('DescartesAdministratorLogin', 'login'));
		}
		
	}
