<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveArray extends BasePrimitive
	{
		/**
		 * @return PrimitiveString
		 */
		public static function create($name)
		{
			return new self($name);
		}
		
		public function notEmpty($value)
		{
			return is_array($value) && count($value);
		}
	}
?>