<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
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