<?php
	namespace ewgraFramework;
	
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
			if (!isset($this->primitives[$name]))
				throw MissingArgumentException::create('known nothing about '.$name);
			
			return $this->primitives[$name];
		}
		
		public function getPrimitiveByScopeKey($key)
		{
			foreach ($this->getPrimitives() as $primitive) {
				if ($primitive->getScopeKey() == $key)
					return $primitive;
			}

			return null;
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
				$primitive->import($scope);
			
			return $this;
		}
		
		/**
		 * @return Form
		 */
		public function importMore(array $scope)
		{
			foreach ($scope as $scopeKey => $value) {
				if ($primitive = $this->getPrimitiveByScopeKey($scopeKey))
					$primitive->import($scope);
			}
			
			return $this;
		}
		
		public function clean()
		{
			foreach ($this->getPrimitives() as $primitive)
				$primitive->clean();
			
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

		public function getValue($primitiveName)
		{
			return $this->getPrimitive($primitiveName)->getValue();
		}

		public function getSafeValue($primitiveName)
		{
			return $this->getPrimitive($primitiveName)->getSafeValue();
		}
	}
?>