<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
	*/
	class PhpView extends BaseView
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
		public function loadLayout($file)
		{
			$this->layoutFile = $file['path'];
			$this->includeFiles = $this->getLayoutIncludeFiles($file['id']);
			
			return $this;
		}
		
		public function transform(Model $model)
		{
			ob_start();
			
			foreach($this->includeFiles as $file)
				require($file);
				
			require($this->layoutFile);

			return ob_get_clean();
		}
		
		public function toString()
		{
			return __FILE__ . '@' . __LINE__;
		}
	}
?>
