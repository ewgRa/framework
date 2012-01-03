<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class EmptyView implements ViewInterface
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
			return '';
		}

		public function toString()
		{
			return '';
		}
	}
?>