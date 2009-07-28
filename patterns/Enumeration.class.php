<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class Enumeration
	{
		private $id = null;
		
		protected $names = array();

		/**
		 * @return Enumeration
		 */
		protected function __construct($id)
		{
			if(!isset($this->names[$id]))
			{
				throw MissingArgumentException::create(
					'known nothing about id='.$id
				);
			}
			
			$this->id = $id;
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		public function getName()
		{
			return $this->names[$this->getId()];
		}
		
		public function getNames()
		{
			return $this->names;
		}
		
		public function __toString()
		{
			return (string)$this->getName();
		}
	}
?>