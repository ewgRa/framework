<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SqlEqExpression
	{
		private $field = null;
		
		private $value = null;
		
		/**
		 * @return SqlEqExpression
		 */
		public function __construct($field, $value)
		{
			$this->field = $field;
			$this->value = $value;
		}
		
		/**
		 * @return SqlEqExpression
		 */
		public function setField($field)
		{
			$this->field = $field;
			return $this;
		}
		
		public function getField()
		{
			return $this->field;
		}
		
		/**
		 * @return SqlEqExpression
		 */
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