<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ModelAndView
	{
		/**
		 * @var ViewInterface
		 */
		private $view = null;

		/**
		 * @var Model
		 */
		private $model = null;

		/**
		 * @return ModelAndView
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return ModelAndView
		 */
		public function setView(ViewInterface $view)
		{
			$this->view = $view;

			return $this;
		}

		/**
		 * @return ViewInterface
		 */
		public function getView()
		{
			return $this->view;
		}

		public function hasView()
		{
			return !is_null($this->view);
		}

		/**
		 * @return ModelAndView
		 */
		public function setModel(Model $model)
		{
			$this->model = $model;
			return $this;
		}

		/**
		 * @return Model
		 */
		public function getModel()
		{
			if (!$this->model)
				$this->model = Model::create();

			return $this->model;
		}

		public function render()
		{
			Assert::isTrue($this->hasView(), 'no view for render');

			return $this->view->transform($this->getModel());
		}
	}
?>