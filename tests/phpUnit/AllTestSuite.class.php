<?php
	/* $Id$ */

	if (!defined('ROOT_SUITE_DIR')) {
		require_once(dirname(__FILE__).'/runTestSuite.php');
	}
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class AllTestSuite extends FrameworkTestSuite
	{
		public static function suite()
		{
			return new self;
		}
	}
?>