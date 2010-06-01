<?php
	/* $Id: AttachedAliases.class.php 174 2009-03-13 06:53:04Z ewgraf $ */

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