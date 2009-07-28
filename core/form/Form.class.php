<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Form
	{
		private $primitives = array();
		
		/**
		 * @return Form
		 */
		public static function create()
		{
			return new self;
		}
		
		public function getPrimitives()
		{
			return $this->primitives;
		}
		
		public function getPrimitive($name)
		{
			if(!isset($this->primitives[$name]))
				throw DefaultException::create('known nothing about ' . $name);
			
			return $this->primitives[$name];
		}
		
		/**
		 * @return Form
		 */
		public function addPrimitive(BasePrimitive $primitive)
		{
			$this->primitives[$primitive->getName()] = $primitive;
			return $this;
		}

		/**
		 * @return Form
		 */
		public function import(array $scope)
		{
			foreach ($this->getPrimitives() as $primitive)
			{
				if(isset($scope[$primitive->getScopeKey()]))
					$primitive->import($scope[$primitive->getScopeKey()]);
			}
			
			return $this;
		}
	}
?>