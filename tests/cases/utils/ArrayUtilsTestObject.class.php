<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ArrayUtilsTestObject
	{
		private $id = null;
		
		public function __construct()
		{
			$this->id = rand();
		}
		
		public function getId()
		{
			return $this->id;
		}
	}
?>