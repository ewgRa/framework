<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveEnumerationTestClass extends Enumeration
	{
		const TEST = 1;
		
		protected $names = array(
			self::TEST => 'test'
		);
		
		/**
		 * @return PrimitiveEnumerationTestClass
		 */
		public static function create($id)
		{
			return new self($id);
		}
	}
?>