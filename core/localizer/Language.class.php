<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Language
	{
		private $abbr = null;
		private $id   = null;

		/**
		 * @return Language
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getAbbr()
		{
			return $this->abbr;
		}
		
		/**
		 * @return Language
		 */
		public function setAbbr($abbr)
		{
			$this->abbr = $abbr;
			return $this;
		}

		public function getId()
		{
			return $this->id;
		}
		
		/**
		 * @return Language
		 */
		public function setId($id)
		{
			$this->id = (int)$id;
			return $this;
		}
	}
?>