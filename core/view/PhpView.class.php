<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PhpView implements ViewInterface
	{
		private $layoutFile = null;
		
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
		public function loadLayout(File $layout)
		{
			Assert::isNotNull($layout->getPath());
			
			$this->layoutFile = $layout->getPath();
			
			return $this;
		}
		
		public function transform(Model $model)
		{
			Assert::isNotNull($this->layoutFile);
			
			ob_start();

			require($this->layoutFile);

			return ob_get_clean();
		}
		
		public function toString()
		{
			return __FILE__ . '@' . __LINE__;
		}
	}
?>