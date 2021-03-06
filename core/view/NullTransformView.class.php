<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NullTransformView implements ViewInterface
	{
		/**
		 * @return NullTransformView
		 */
		public static function create()
		{
			return new self;
		}

		public function transform(Model $model)
		{
			return $model;
		}

		public function toString()
		{
			return null;
		}
	}
?>