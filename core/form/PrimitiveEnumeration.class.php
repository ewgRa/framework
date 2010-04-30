<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveEnumeration extends BasePrimitive
	{
		private $class = null;
		
		/**
		 * @return PrimitiveEnumeration
		 */
		public static function create($name)
		{
			return new self($name);
		}
		
		/**
		 * @return PrimitiveEnumeration
		 */
		public function setClass($class)
		{
			$this->class = $class;
			return $this;
		}

		/**
		 * @return PrimitiveEnumeration
		 */
		public function importValue($value)
		{
			Assert::isNotNull($this->class);
			
			$result = parent::importValue($value);
			
			if (!$this->hasErrors() && $this->getValue()) {
				$this->setValue(
					call_user_func(array($this->class,'create'), $value)
				);
			}
			
			return $result;
		}
	}
?>