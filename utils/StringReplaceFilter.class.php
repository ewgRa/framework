<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class StringReplaceFilter
	{
		private $search		= array();
		private $replace	= array();

		/**
		 * @return StringReplaceImportFilter
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return StringReplaceImportFilter
		 */
		public function setSearch(array $search)
		{
			$this->search = $search;
			return $this;
		}
		
		public function getSearch()
		{
			return $this->search;
		}
		
		/**
		 * @return StringReplaceImportFilter
		 */
		public function setReplace(array $replace)
		{
			$this->replace = $replace;
			return $this;
		}
		
		public function getReplace()
		{
			return $this->replace;
		}
		
		public function apply($string)
		{
			return
				str_replace(
					$this->getSearch(),
					$this->getReplace(),
					$string
				);
		}
	}
?>