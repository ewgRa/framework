<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveInteger extends BasePrimitive
	{
		/**
		 * @return PrimitiveInteger
		 */
		public static function create($name)
		{
			return new self($name);
		}

		/**
		 * @return PrimitiveInteger
		 */
		public function import($scope)
		{
			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue() !== null)
				$this->setValue((int)$this->getValue());

			return $result;
		}
	}
?>