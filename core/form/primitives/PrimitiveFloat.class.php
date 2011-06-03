<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveFloat extends BasePrimitive
	{
		/**
		 * @return PrimitiveFloat
		 */
		public static function create($name)
		{
			return new self($name);
		}

		/**
		 * @return PrimitiveFloat
		 */
		public function import($scope)
		{
			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue())
				$this->setValue((float)$this->getValue());

			return $result;
		}

		public function isEmpty($value)
		{
			return $value === null;
		}
	}
?>