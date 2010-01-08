<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveObject extends BasePrimitive
	{
		private $objectClass = null;
		
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
		public function setObjectClass($class)
		{
			$this->objectClass = $class;
			return $this;
		}

		/**
		 * @return BasePrimitive
		 */
		public function import($value)
		{
			Assert::isNotNull($this->objectClass);
			
			$result = parent::import($value);
			
			$classDA = call_user_func(array($this->objectClass,'da'));
			
			if (!$this->hasErrors() && $this->getValue())
				$this->setValue($classDA->getById($this->getValue()));
			
			return $result;
		}
	}
?>