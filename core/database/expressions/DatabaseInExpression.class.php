<?php
	/* $Id: AttachedAliases.class.php 174 2009-03-13 06:53:04Z ewgraf $ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class DatabaseInExpression
	{
		private $field = null;
		
		private $values = array();
		
		public function __construct($field, $values)
		{
			$this->field = $field;
			$this->values = $values;
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
		
		public function setValues(array $values)
		{
			$this->values = $values;
			return $this;
		}
		
		public function getValues()
		{
			return $this->values;
		}
	}
?>