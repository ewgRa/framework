<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveObjectList extends PrimitiveArray
	{
		private $class = null;

		/**
		 * @return PrimitiveObjectList
		 */
		public static function create($name)
		{
			return new self($name);
		}

		/**
		 * @return PrimitiveObjectList
		 */
		public function setClass($class)
		{
			$this->class = $class;
			return $this;
		}

		/**
		 * @return PrimitiveObjectList
		 */
		public function import($scope)
		{
			Assert::isNotNull($this->class);

			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue() !== null) {
				$value = $this->getValue();

				foreach ($value as $key => $objectId) {
					$classDA = call_user_func(array($this->class, 'da'));
					$object = $classDA->getById($objectId);

					if (!$object) {
						$this->dropValue();
						$this->markMissing();
						break;
					}

					$value[$key] = $object;
				}

				$this->setValue($value);
			}

			return $result;
		}
	}
?>