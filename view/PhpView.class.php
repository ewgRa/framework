<?php
	/* $Id$ */

	// FIXME: tested?
	class PhpView extends BaseView
	{
		private $layoutFile = null;
		private $includeFiles = null;
		
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
		
		public function transform(array $model)
		{
			ob_start();
			
			foreach($this->includeFiles as $file)
				require($file);
				
			require($this->layoutFile);

			$result = ob_get_clean();
			
			return $result;
		}
		
		public function toString()
		{
			return __FILE__ . '@' . __LINE__;
		}
	}
?>
