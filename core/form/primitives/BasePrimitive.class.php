<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BasePrimitive implements PrimitiveInterface
	{
		const MISSING_ERROR	= 'missing';
		const WRONG_ERROR	= 'wrong';
		
		private $name 		= null;
		private $scopeKey	= null;
		private $rawValue	= null;
		private $defaultValue = null;
		private $value		= null;
		private $errors		= array();
		private $errorLabels = array();
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
		public function setDefaultValue($value)
		{
			$this->defaultValue = $value;
			return $this;
		}

		public function getDefaultValue()
		{
			return $this->defaultValue;
		}
		
		public function getSafeValue()
		{
			return
				$this->getValue()
					? $this->getValue()
					: $this->getDefaultValue();
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
		
		public function dropValue()
		{
			$this->value = null;
			return $this;
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
		
		public function isRequired()
		{
			return $this->required;
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function addError($errorCode)
		{
			$this->errors[] = $errorCode;
			return $this;
		}
		
		public function getErrors()
		{
			return $this->errors;
		}
		
		public function hasErrors()
		{
			return (count($this->getErrors()) > 0);
		}
		
		public function hasError($errorCode)
		{
			return in_array($errorCode, $this->getErrors());
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function setErrorLabel($errorCode, $text)
		{
			$this->errorLabels[$errorCode] = $text;
			return $this;
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function setMissingErrorLabel($text)
		{
			$this->errorLabels[self::MISSING_ERROR] = $text;
			return $this;
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function setWrongErrorLabel($text)
		{
			$this->errorLabels[self::WRONG_ERROR] = $text;
			return $this;
		}
		
		public function getErrorLabel($errorCode)
		{
			if (!isset($this->errorLabels[$errorCode]))
				throw MissingArgumentException::create();
			
			return $this->errorLabels[$errorCode];
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function clean()
		{
			$this->rawValue = null;
			$this->value = null;
			$this->errors = null;
			
			return $this;
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
			
			$this->clean();
			
			$this->setRawValue($value);
			
			if ($this->isWrong($value))
				$this->markWrong();
			else if ($this->isRequired() && $this->isEmpty($value)) {
				$this->markMissing();
			} else if(!$this->isEmpty($value))
				$this->setValue($value);

			return $this;
		}
		
		public function isEmpty($value)
		{
			return !$value;
		}
		
		public function isWrong($value)
		{
			return false;
		}
		
		public function markMissing()
		{
			$this->addError(self::MISSING_ERROR);
			return $this;
		}

		public function markWrong()
		{
			$this->addError(self::WRONG_ERROR);
			return $this;
		}
	}
?>