<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveObjectTestObject
	{
		private $id = null;
		
		public static function create()
		{
			return new self;
		}
		
		public static function da()
		{
			return PrimitiveObjectTestObjectDA::create();
		}
		
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}

		public function getId()
		{
			return $this->id;
		}
	}
?>