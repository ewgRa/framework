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