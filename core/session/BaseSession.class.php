<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseSession extends Model implements SessionInterface
	{
		protected $isStarted = false;

		public function isStarted()
		{
			return $this->isStarted;
		}
	}
?>