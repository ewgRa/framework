<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BasePrimitive
	{
		private $name 		= null;
		private $scopeKey	= null;
		private $rawValue	= null;
		private $value		= null;
		private $errors		= array();
		private $errorLabel = array();
		private $required	= null;
		
		protected function __construct($name)
		{
			$this->setName($name);
			$this->setScopeKey($name);
		}
			
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
		public function setScopeKey($key)
		{
			$this->scopeKey = $key;
			return $this;
		}
		
		public function getScopeKey()
		{
			return $this->scopeKey;
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
			return $this->value;
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
		public function setRequired($required = true)
		{
			$this->required = ($required === true);
			return $this;
		}
		
		public function getRequired()
		{
			return $this->required;
		}
		
		public function isRequired()
		{
			return $this->required;
		}
		
		public function getErrors()
		{
			return $this->errors;
		}
		
		public function hasErrors()
		{
			return (count($this->getErrors()) > 0);
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function setErrorLabel($errorCode, $text)
		{
			$this->errorLabel[$errorCode] = $text;
			return $this;
		}
		
		public function getErrorLabel($errorCode)
		{
			if (!isset($this->errorLabel[$errorCode]))
				throw MissingArgumentException::create();
			
			return $this->errorLabel[$errorCode];
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function import($scope)
		{
			$value =
				isset($scope[$this->getScopeKey()])
					? $scope[$this->getScopeKey()]
					: null;
			
			return $this->importValue($value);
		}
		
		public function importValue($value)
		{
			if ($value && $value !== '') {
				$this->setRawValue($value);
				$this->setValue($value);
			} else if($this->isRequired())
				$this->errors[] = PrimitiveErrors::MISSING;

			return $this;
		}
	}
?>