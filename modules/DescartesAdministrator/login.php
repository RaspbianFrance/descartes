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
			if (empty($_POST['login']) || empty($_POST['password']))
			{
				return $this->render("DescartesAdministrator/login/default");	
			}

			$pseudo = $_POST['login'];
			$password = $_POST['password'];
				
			if ($pseudo != DESCARTESADMINISTRATOR_LOGIN || $password != DESCARTESADMINISTRATOR_PASSWORD)
			{
				return $this->render("DescartesAdministrator/login/default", array(
					"message" => "Erreur lors de la connexion"
				));
			}

			$_SESSION['connect'] = true;
			return header('Location: ' . $this->generateUrl("admin"));
		}
		
		public function logout()
		{
			session_unset();
			session_destroy();
			return header('Location: ' . $this->generateUrl());
		}
		
	}
