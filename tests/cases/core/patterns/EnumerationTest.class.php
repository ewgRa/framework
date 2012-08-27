<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class EnumerationTest extends \ewgraFramework\Enumeration
	{
		const TEST	= 1;
		const TEST2	= 2;

		protected $names = array(
			self::TEST 	=> 'Test',
			self::TEST2 => 'test2'
		);

		/**
		 * @return EnumerationTest
		 */
		public static function create($id)
		{
			return parent::create($id);
		}
	}
?>