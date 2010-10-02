<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveString extends BasePrimitive
	{
		/**
		 * @return PrimitiveString
		 */
		public static function create($name)
		{
			return new self($name);
		}

		/**
		 * @return BasePrimitive
		 */
		public function importValue($value)
		{
			$result = parent::importValue($value);
			
			$this->setValue((string)$this->getValue());

			return $result;
		}
	}
?>