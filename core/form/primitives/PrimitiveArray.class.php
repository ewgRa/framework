<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class PrimitiveArray extends BasePrimitive
	{
		/**
		 * @return PrimitiveArray
		 */
		public static function create($name)
		{
			return new self($name);
		}

		public function isEmpty($value)
		{
			return !is_array($value) || !count($value);
		}
	}
?>