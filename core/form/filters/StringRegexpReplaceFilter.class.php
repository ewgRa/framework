<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class StringRegexpReplaceFilter implements FilterInterface
	{
		private $search		= array();
		private $replace	= array();

		/**
		 * @return StringRegexpReplaceFilter
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return StringRegexpReplaceFilter
		 */
		public function addReplacement($search, $replace)
		{
			$this->search[]	 = $search;
			$this->replace[] = $replace;

			return $this;
		}

		public function apply($var)
		{
			return
				preg_replace(
					$this->search,
					$this->replace,
					$var
				);
		}
	}
?>