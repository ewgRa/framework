<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DirTestCase extends FrameworkTestCase
	{
		public function testDeleteDir()
		{
			$dirName = CACHE_DIR . DIRECTORY_SEPARATOR . __CLASS__;
			
			if (!file_exists($dirName))
				mkdir($dirName);
			
			file_put_contents($dirName . DIRECTORY_SEPARATOR . 'file', rand());
			
			Dir::deleteDir($dirName);
			
			$this->assertFalse(file_exists($dirName));
		}
	}
?>