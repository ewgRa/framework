<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveObject extends BasePrimitive
	{
		private $class = null;
		
		/**
		 * @return PrimitiveObject
		 */
		public static function create($name)
		{
			return new self($name);
		}
		
		/**
		 * @return PrimitiveObject
		 */
		public function setClass($class)
		{
			$this->class = $class;
			return $this;
		}

		/**
		 * @return BasePrimitive
		 */
		public function importValue($value)
		{
			Assert::isNotNull($this->class);
			
			$result = parent::importValue($value);
			
			if (!$this->hasErrors() && $this->getValue()) {
				$classDA = call_user_func(array($this->class, 'da'));
				$this->setValue($classDA->getById($this->getValue()));
			}
			
			return $result;
		}
	}
?>