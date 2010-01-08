<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Form
	{
		private $primitives = array();
		
		private $imported	= null;
		
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
			if (!isset($this->primitives[$name]))
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

		public function isImported()
		{
			return $this->imported;
		}
		
		/**
		 * @return Form
		 */
		public function import(array $scope)
		{
			$this->imported = (count($scope) > 0);
			
			if ($this->imported) {
				foreach ($this->getPrimitives() as $primitive)
					$primitive->import($scope);
			}
			
			return $this;
		}
		
		public function hasErrors()
		{
			foreach ($this->getPrimitives() as $primitive) {
				if ($primitive->hasErrors())
					return true;
			}
			
			return false;
		}

		public function getErrors()
		{
			$result = array();
			
			foreach ($this->getPrimitives() as $primitive) {
				if ($primitive->hasErrors())
					$result[$primitive->getName()] = $primitive->getErrors();
			}
			
			return $result;
		}
	}
?>