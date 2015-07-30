<?php
	namespace DescartesAdministrator;

	/**
	 * page de login Admin
	 */
	class login extends \Controller
	{
		public function before()
		{
			if (!empty($_SESSION['connect']) && !$_SESSION['connect'])
			{
				return header('Location: ' . $this->generateUrl("admin"));
			}
		}
		
		public function byDefault()
		{
			if (!empty($_POST['login']) && !empty($_POST['password']))
			{
				$pseudo = $_POST['login'];
				$password = $_POST['password'];
				
				if ($pseudo == ADMIN_LOGIN && $password == ADMIN_PASSWORD)
				{
					$_SESSION['connect'] = true;
					header('Location: ' . $this->generateUrl("admin"));
					return true;
				}
				
				return $this->render("loginIndex", array(
					"message" => "Erreur lors de la connexion"
				));
			}
		
			$this->render("DescartesAdministrator/login/default");	
		}
		
		public function logout()
		{
			session_unset();
			session_destroy();
			return header('Location: ' . $this->generateUrl());
		}
		
	}
