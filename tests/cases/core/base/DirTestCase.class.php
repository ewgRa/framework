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
			$dirName = TMP_DIR . DIRECTORY_SEPARATOR . __CLASS__;
			
			if (!file_exists($dirName))
				mkdir($dirName);
				
			file_put_contents($dirName . DIRECTORY_SEPARATOR . 'file', rand());
			
			$dir = Dir::create()->setPath($dirName);
			
			$this->assertTrue($dir->isExists());
			
			$dir->delete();
			
			$this->assertFalse(file_exists($dirName));
		}

		public function testCreateDir()
		{
			$dirName = TMP_DIR . DIRECTORY_SEPARATOR . __CLASS__;
			
			$dir = Dir::create()->setPath($dirName);

			$dir->make();
			
			$this->assertTrue($dir->isExists());
			
			$dir->delete();
		}
	}
?>