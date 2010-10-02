<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlInExpression extends DatabaseInExpression
	{
		public function __toString()
		{
			return
				'`'.$this->getField().'`' .
				" IN ('" .
					join("', '", MysqlDatabase::escape($this->getValues())) .
				"')";
		}
	}
?>