<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class StringSubstrFilter implements FilterInterface
	{
		private $start		= 0;
		private $count		= null;

		/**
		 * @return StringSubstrFilter
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return StringSubstrFilter
		 */
		public function setStart($start)
		{
			$this->start = $start;
			return $this;
		}

		/**
		 * @return StringSubstrFilter
		 */
		public function setLength($length)
		{
			$this->length = $length;
			return $this;
		}

		public function apply($var)
		{
			return
				\ewgraFramework\StringUtils::substr(
					$var,
					$this->start,
					$this->length
				);
		}
	}
?>