<?php
	namespace ewgraFramework;

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
		public function import($scope)
		{
			Assert::isNotNull($this->class);

			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue() !== null) {
				$classDA = call_user_func(array($this->class, 'da'));
				$value = $classDA->getById($this->getValue());

				$this->setValue($value);

				if ($this->getValue() === null)
					$this->markMissing();
			}

			return $result;
		}
	}
?>