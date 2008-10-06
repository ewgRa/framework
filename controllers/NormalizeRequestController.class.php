<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * //FIXME: tested?
	*/
	class NormalizeRequestController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest()
		{
			$result = parent::handleRequest();
			
			$this->normalizeRequest();
			
			return $result;
		}
		
		/**
		 * @return NormalizeRequestController
		 */
		private function normalizeRequest()
		{
			if(function_exists('set_magic_quotes_runtime'))
				set_magic_quotes_runtime(0);
			
			if(function_exists( 'get_magic_quotes_gpc') && get_magic_quotes_gpc())
			{
				$this->
					strips($_GET)->
					strips($_POST)->
					strips($_COOKIE)->
					strips($_REQUEST);
				
				if(isset($_SERVER['PHP_AUTH_USER']))
					$this->strips($_SERVER['PHP_AUTH_USER']);

				if(isset($_SERVER['PHP_AUTH_PW']))
					$this->strips($_SERVER['PHP_AUTH_PW']);
			}
			
			return $this;
		}

		/**
		 * @return NormalizeRequestController
		 */
		private function strips(&$el)
		{
			if(is_array($el))
			{
				foreach($el as &$v)
					$this->strips($v);
			}
			else
				$el = stripslashes($el);
				
			return $this;
			
		}
	}
?>