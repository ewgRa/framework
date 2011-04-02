<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class DatabaseEqExpression
	{
		private $field = null;

		private $value = null;

		public function __construct($field, $value)
		{
			$this->field = $field;
			$this->value = $value;
		}

		public function setField($field)
		{
			$this->field = $field;
			return $this;
		}

		public function getField()
		{
			return $this->field;
		}

		public function setValue($value)
		{
			$this->value = $value;
			return $this;
		}

		public function getValue()
		{
			return $this->value;
		}
	}
?>