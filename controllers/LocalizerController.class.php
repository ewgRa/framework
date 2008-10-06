<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * //FIXME: tested?
	*/
	class LocalizerController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest()
		{
			$result = parent::handleRequest();
			
			Localizer::me()->defineLanguage();
			
			return $result;
		}
	}
?>