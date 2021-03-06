<?php
	namespace ewgraFramework;

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

		public function getRawDefaultValue()
		{
			$defaultValue = $this->getDefaultValue();

			if ($defaultValue)
				return $defaultValue->getId();

			return null;
		}

		/**
		 * @return PrimitiveEnumeration
		 */
		public function import($scope)
		{
			Assert::isNotNull($this->class);

			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue() !== null) {
				try {
					$this->setValue(
						call_user_func(
							array($this->class, 'create'),
							$this->getValue()
						)
					);
				} catch (MissingArgumentException $e) {
					$this->markMissing();
				}
			}

			return $result;
		}
	}
?>