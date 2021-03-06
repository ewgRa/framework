<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NullView implements ViewInterface
	{
		/**
		 * @return NullView
		 */
		public static function create()
		{
			return new self;
		}

		public function transform(Model $model)
		{
			return null;
		}

		public function toString()
		{
			return null;
		}
	}
?>