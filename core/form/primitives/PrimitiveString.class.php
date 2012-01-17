<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class PrimitiveString extends RangePrimitive
	{
		/**
		 * @return PrimitiveString
		 */
		public static function create($name)
		{
			return new self($name);
		}

		public function getRangeValue()
		{
			return StringUtils::getLength($this->getValue());
		}

		/**
		 * @return PrimitiveString
		 */
		public function import($scope)
		{
			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue() !== null)
				$this->setValue((string)$this->getValue());

			return $result;
		}
	}
?>