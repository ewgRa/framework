<?php
	namespace ewgraFramework\tests;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveObjectTestObjectDA
	{
		public static function create()
		{
			return new self;
		}
		
		public function getById($id)
		{
			return PrimitiveObjectTestObject::create()->setId($id);
		}
	}
?>