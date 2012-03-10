<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Pager
	{
		private $total = null;
		private $offset = null;
		private $limit = null;

		/**
		 * @return Pager
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return Pager
		 */
		public function setTotal($total)
		{
			$this->total = $total;
			return $this;
		}

		public function getTotal()
		{
			return $this->total;
		}

		/**
		 * @return Pager
		 */
		public function setOffset($offset)
		{
			$this->offset = $offset;
			return $this;
		}

		public function getOffset()
		{
			return $this->offset;
		}

		/**
		 * @return Pager
		 */
		public function setLimit($limit)
		{
			$this->limit = $limit;
			return $this;
		}

		public function getLimit()
		{
			return $this->limit;
		}
	}
?>