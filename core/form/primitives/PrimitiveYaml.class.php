<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveYaml extends PrimitiveString
	{
		/**
		 * @return PrimitiveYaml
		 */
		public static function create($name)
		{
			return new self($name);
		}

		/**
		 * @return PrimitiveYaml
		 */
		public function import($scope)
		{
			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue() !== null)
				$this->setValue(Yaml::loadString($this->getValue()));

			return $result;
		}

		public function isWrong($value)
		{
			return
				!$this->isEmpty($value)
				&& Yaml::loadString($value) == array();
		}
	}
?>