<?php
    namespace descartes;
    /**
     * This class is the parent of all controllers
	 */
	class Controller
    {
		/**
         * Render a template for displaying
         * @param string $template : Template name without extension, possibly preceed with a directory name
         * @param array|null $variables : Array of variables to transfer to the template, format name => value
         * @param string $dir_template_path : Template directory path, if not defined we use PWD_TEMPLATES constant
         * @param string $extension : The extension of the template file, if not defined we use ".php"
         * @return true
		 */
		protected function render (string $template, ?array $variables = array(), string $dir_template_path = PWD_TEMPLATES, string $template_extension = '.php') : bool
		{
			foreach ($variables as $clef => $value)
			{
				$$clef = $value;
			}

			$template_path = $dir_template_path . '/' . $template . $template_extension;

			if (!is_readable($template_path))
            {
                throw new exceptions\DescartesExceptionTemplateNotReadable('Template ' . $template_path . ' is not readable.');
            }

            require $template_path;
            return true;
		}

        
        /**
         * Print text after escaping it for security
		 *
         * @param string $text : Texte to print
         * @param bool $nl2br : If true, replace "\n" by "<br/>", false by default
         * @param bool $escape_quotes : If true, turn quotes and double quotes into html entities, true by default
         * @param bool $echo : If true, print text, else return it without printing
		 *
         * @return string : modified text
		 */
		public static function s (string $text, bool $nl2br = false, bool $escape_quotes = true, bool $echo = true) : string
		{
			$text = $escape_quotes ? htmlspecialchars($text, ENT_QUOTES) : htmlspecialchars($text, ENT_NOQUOTES);
			$text = $nl2br ? nl2br($text) : $text;
            
            if ($echo)
			{
                echo $text;
			}
            
            return $text;
		}

        /**
         * Verify if we received a valid csrf. Always return true if not in http context
         * @param string|null $csrf : CSRF token to verify, if ommited, we use $_GET['csrf'], then $_POST['csrf']
         * @param string|null $reference_csrf : CSRF token generated by system, if ommited we use $_SESSION['csrf']
         * @return bool : True if valid, false else
		 */
		public static function verify_csrf (?string $csrf = null, ?string $reference_csrf = null) : bool
        {
            $csrf = $csrf ?? $_POST['csrf'] ?? $_GET['csrf'];
            $reference_csrf = $reference_csrf ?? $_SESSION['csrf'];

            if (http_response_code() === FALSE)
			{
				return true;
			}

			if ($csrf != $_SESSION['csrf'])
			{
                return false;
			}
			
            return true;
        }

        /**
         * Redirect to an url
         * @param string $url : URL to redirect to
         * @param int $http_code : Http code to send (default 302)
         * @return null
         */
        public static function redirect (string $url, int $http_code = 302)
        {
            header('Location: ' . $url, true, $http_code);
            return null;
        }
	} 
