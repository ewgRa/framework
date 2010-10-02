<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NullCache extends BaseCache
	{
		/**
		 * @return NullCache
		 */
		public static function create()
		{
			return new self;
		}

		public function get(CacheTicket $ticket)
		{
			return null;
		}

		/**
		 * @return NullCache
		 */
		public function set(CacheTicket $ticket, $data)
		{
			return $this;
		}

		/**
		 * @return NullCache
		 */
		public function dropByKey($key)
		{
			return $this;
		}
		
		/**
		 * @return NullCache
		 */
		public function clean()
		{
			return $this;
		}
	}
?>