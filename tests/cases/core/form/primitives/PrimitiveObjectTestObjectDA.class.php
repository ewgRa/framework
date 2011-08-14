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
			if ($id != PrimitiveObjectTestObject::EXISTS_ID)
				return null;

			return PrimitiveObjectTestObject::create()->setId($id);
		}
	}
?>