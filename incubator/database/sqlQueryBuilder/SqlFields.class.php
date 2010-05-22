<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SqlFields
	{
		private $fields	= array();

		/**
		 * @return SqlFields
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return SqlQuery
		 */
		public function setFields(array $fields)
		{
			$this->fields = $fields;
			return $this;
		}
		
		public function getFields()
		{
			return $this->fields;
		}
	}
?>