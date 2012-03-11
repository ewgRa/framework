<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseDialect extends Singleton implements DatabaseDialectInterface
	{
		public function getLimitByPager(Pager $pager)
		{
			return $this->getLimit($pager->getLimit(), $pager->getOffset());
		}
	}
?>