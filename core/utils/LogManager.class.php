<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class LogManager extends \ewgraFramework\Singleton
	{
		/**
		 * @return LogManager
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		public function store($message)
		{
			error_log(mb_substr($message, 0, 2048, 'utf-8'));
		}
	}
?>