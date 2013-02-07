<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveDateTime extends BasePrimitive
	{
		private $format = null;

		/**
		 * @return PrimitiveDateTime
		 */
		public static function create($name)
		{
			return new self($name);
		}

		/**
		 * @return PrimitiveDateTime
		 */
		public function setFormat($format)
		{
			$this->format = $format;
			return $this;
		}

		public function getFormat()
		{
			return $this->format;
		}

		/**
		 * @return PrimitiveDateTime
		 */
		public function import($scope)
		{
			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue() !== null) {
				$value =
					$this->getFormat()
						? DateTime::createFromFormat($this->getFormat(), $this->getValue())
						: DateTime::createFromString($this->getValue());

				$this->setValue($value);
			}

			return $result;
		}
	}
?>