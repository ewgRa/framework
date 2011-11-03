<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface DatabaseInsertQueryInterface extends DatabaseQueryInterface
	{
		public function setPrimaryField($primaryField);

		public function getPrimaryField();
	}
?>