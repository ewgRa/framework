<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class PrimitiveBoolean extends BasePrimitive
	{
		/**
		 * @return PrimitiveBoolean
		 */
		public static function create($name)
		{
			return new self($name);
		}

		/**
		 * @return PrimitiveBoolean
		 */
		public function import($scope)
		{
			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue() !== null)
				$this->setValue($this->getValue() ? true : false);

			return $result;
		}
	}
?>