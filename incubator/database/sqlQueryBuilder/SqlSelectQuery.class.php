<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class SqlSelectQuery
	{
		private $fields	= null;

		private $from	= null;

		private $where	= null;
		
		/**
		 * @return SqlQuery
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

		/**
		 * @return SqlQuery
		 */
		public function setFrom($from)
		{
			if (!is_object($from))
				$from = SqlTable::create($from);
			
			$this->from = $from;
			return $this;
		}
		
		public function getFrom()
		{
			return $this->from;
		}

		/**
		 * @return SqlQuery
		 */
		public function setWhere($where)
		{
			$this->where = $where;
			return $this;
		}
		
		public function getWhere()
		{
			return $this->where;
		}
	}
?>