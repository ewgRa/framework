<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PhpView implements ViewInterface
	{
		/**
		 * @var File
		 */
		private $layoutFile = null;
		
		/**
		 * @return PhpView
		 */
		public static function create()
		{
			return new self;
		}

		public static function includeFile($path, Model $model = null)
		{
			if ($model && $model->getData()) {
				foreach ($model->getData() as $varName => $value)
					$$varName = $value;
			}

			include $path;
		}
		
		/**
		 * @return PhpView
		 */
		public function loadLayout(File $layout)
		{
			Assert::isNotNull($layout->getPath());
			
			$this->layoutFile = $layout;
			
			return $this;
		}
		
		public function transform(Model $model)
		{
			Assert::isNotNull($this->layoutFile);
			
			if ($model && $model->getData()) {
				foreach ($model->getData() as $varName => $value)
					$$varName = $value;
			}
			
			ob_start();

			require($this->layoutFile->getPath());

			return ob_get_clean();
		}
		
		public function toString()
		{
			Assert::isNotNull($this->layoutFile->getPath());
			
			return $this->layoutFile->getContent();
		}
	}
?>