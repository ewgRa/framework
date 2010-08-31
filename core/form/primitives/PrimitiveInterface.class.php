<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface PrimitiveInterface
	{
		public static function create($name);
		
		/**
		 * @return PrimitiveInterface
		 */
		public function setScopeKey($key);
		
		public function getScopeKey();

		public function setRequired($required = true);
		
		public function isRequired();

		public function getErrors();
		
		public function hasErrors();
		
		public function hasError($errorCode);
		
		/**
		 * @return PrimitiveInterface
		 */
		public function setErrorLabel($errorCode, $text);
		
		public function getErrorLabel($errorCode);
		
		/**
		 * @return PrimitiveInterface
		 */
		public function clean();
		
		/**
		 * @return PrimitiveInterface
		 */
		public function import($scope);
		
		public function importValue($value);
	}
?>