<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	class PrimitiveYaml extends BasePrimitive
	{
		/**
		 * @return PrimitiveYaml
		 */
		public static function create()
		{
			return new self;
		}

		public function import($value)
		{
			parent::import($value);
			$this->setValue(Yaml::loadString($this->getRawValue()));
			return $this;
		}
	}
?>