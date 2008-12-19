<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	abstract class BasePrimitive
	{
		private $name 		= null;
		private $scopeKey 	= null;
		private $rawValue	= null;
		
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

		public function getValue()
		{
			return
				in_array($this->getRawValue(), $this->getNullValues())
				|| ($this->isFalseValueIsNull() && !$this->getRawValue())
					? null
					: $this->getRawValue();
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
			return $this;
		}
	}
?>