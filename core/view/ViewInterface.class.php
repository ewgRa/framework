<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface ViewInterface
	{
		public static function create();

		public function transform(Model $model);

		public function toString();
	}
?>