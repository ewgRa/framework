<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BasePrimitive
	{
		private $name 		= null;
		private $scopeKey 	= null;
		private $rawValue	= null;
		private $value		= null;
		
		private $nullValues 		= array();
		private $falseValueIsNull 	= false;
		
		/**
		 * @return BasePrimitive
		 */
		public function setRawValue($value)
		{
			$this->rawValue = $value;
			return $this;
		}
		
		public function getRawValue()
		{
			return $this->rawValue;
		}

		/**
		 * @return BasePrimitive
		 */
		public function setValue($value)
		{
			$this->value = $value;
			return $this;
		}

		public function getValue()
		{
			return
				in_array($this->value, $this->getNullValues())
				|| ($this->isFalseValueIsNull() && !$this->value)
					? null
					: $this->value;
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function setName($name)
		{
			$this->name = $name;
			return $this;
		}
		
		public function getName()
		{
			return $this->name;
		}

		/**
		 * @return BasePrimitive
		 */
		public function setNullValues(array $values)
		{
			$this->nullValues = $values;
			return $this;
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function addNullValue($value)
		{
			$this->nullValues[] = $value;
			return $this;
		}
		
		public function getNullValues()
		{
			return $this->nullValues;
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function falseValueIsNull()
		{
			$this->falseValueIsNull = true;
			return $this;
		}
		
		public function isFalseValueIsNull()
		{
			return $this->falseValueIsNull;
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function setScopeKey($key)
		{
			$this->scopeKey = $key;
			return $this;
		}
		
		public function getScopeKey()
		{
			return $this->scopeKey ? $this->scopeKey : $this->getName();
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function import($value)
		{
			$this->setRawValue($value);
			$this->setValue($value);
			return $this;
		}
	}
?>