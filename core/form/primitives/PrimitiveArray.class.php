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
			return !count($value);
		}

		/**
		 * @return PrimitiveArray
		 */
		public function setRawValue($value)
		{
			Assert::isArray($value);
			return parent::setRawValue($value);
		}
	}
?>