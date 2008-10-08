<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	class Language
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
			$this->id = $id;
			return $this;
		}
	}
?>