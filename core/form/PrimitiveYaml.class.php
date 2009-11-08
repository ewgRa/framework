<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveYaml extends BasePrimitive
	{
		/**
		 * @return PrimitiveYaml
		 */
		public static function create($name)
		{
			return new self($name);
		}
		
		/**
		 * @return PrimitiveYaml
		 */
		public function import($value)
		{
			parent::import($value);
			$this->setValue(Yaml::loadString($this->getRawValue()));

			return $this;
		}
	}
?>