<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class EnumerationTest extends Enumeration
	{
		const TEST			= 1;
		const TEST2			= 2;
		
		protected $names = array(
			self::TEST 			=> 'test',
			self::TEST2 		=> 'test2'
		);
		
		/**
		 * @return EnumerationTest
		 */
		public static function create($id)
		{
			return new self($id);
		}
	}
?>