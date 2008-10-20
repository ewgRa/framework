<?php
	/* $Id$ */

	// FIXME: tested?

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class BaseView implements ViewInterface
	{
		/**
		 * @var ViewDA
		 */
		private $da = null;
		
		public function da()
		{
			if(!$this->da)
				$this->da = ViewDA::create();
				
			return $this->da;
		}
		
		protected function getLayoutIncludeFiles($fileId)
		{
			$result = array();
			
			
			foreach($this->da()->getLayouIncludeFiles($fileId) as $file)
			{
				$result[] = str_replace(
					'\\',
					'/',
					realpath(Config::me()->replaceVariables($file['path']))
				);
			}
			
			return $result;
		}
	}
?>
