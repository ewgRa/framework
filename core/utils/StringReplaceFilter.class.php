<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class StringReplaceFilter
	{
		private $search		= array();
		private $replace	= array();

		/**
		 * @return StringReplaceFilter
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return StringReplaceFilter
		 */
		public function addReplacement($search, $replace)
		{
			$this->search[]	 = $search;
			$this->replace[] = $replace;
			return $this;
		}
		
		public function apply($string)
		{
			return
				str_replace(
					$this->search,
					$this->replace,
					$string
				);
		}
	}
?>