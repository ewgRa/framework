<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MysqlEqExpression extends DatabaseEqExpression
	{
		public function __toString()
		{
			return
				$this->getField() .
				" = '".MysqlDatabase::escape($this->getValue())."'";
		}
	}
?>