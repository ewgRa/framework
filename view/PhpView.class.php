<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
	*/
	final class PhpView extends BaseView
	{
		private $layoutFile 	= null;
		private $includeFiles 	= null;
		
		/**
		 * @return PhpView
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return PhpView
		 */
		public function loadLayout($filePath, $fileId = null)
		{
			$this->createLayout($filePath);
			
			if($fileId)
				$this->includeFiles = $this->getLayoutIncludeFiles($fileId);
			
			return $this;
		}
		
		public function transform(Model $model)
		{
			ob_start();
			
			if($this->includeFiles)
			{
				foreach($this->includeFiles as $file)
					require($file);
			}
				
			require($this->layoutFile);

			return ob_get_clean();
		}
		
		public function toString()
		{
			return __FILE__ . '@' . __LINE__;
		}

		/**
		 * @return PhpView
		 */
		private function createLayout($filePath)
		{
			$this->layoutFile = $filePath;
			
			return $this;
		}
	}
?>