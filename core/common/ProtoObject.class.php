<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class ProtoObject extends Singleton
	{
		protected $dbFields = array();

		public function getDbFields()
		{
			return $this->dbFields;
		}
	}
?>