<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FileTestCase extends FrameworkTestCase
	{
		public function testContent()
		{
			$dirName = TMP_DIR.DIRECTORY_SEPARATOR.__CLASS__;

			if (!file_exists($dirName))
				mkdir($dirName);

			$file = \ewgraFramework\File::create()->setPath($dirName.'/'.rand());
			$content = rand();
			$file->setContent($content);

			$this->assertEquals($content, $file->getContent());
		}

		public function testOperations()
		{
			$dirName = TMP_DIR.DIRECTORY_SEPARATOR.__CLASS__;

			if (!file_exists($dirName))
				mkdir($dirName);

			$basename = rand();

			$copy =
				\ewgraFramework\File::create()->setPath(
					$dirName.'/'.$basename.'.copiedtest'
				);

			$dest =
				\ewgraFramework\File::create()->setPath(
					$dirName.'/'.$basename.'.movedtest'
				);

			$file =
				\ewgraFramework\File::create()->setPath(
					$dirName.'/'.$basename.'.test'
				);

			$this->assertEquals($basename.'.test', $file->getBaseName());
			$this->assertFalse($file->isExists());
			$file->setContent('aaa');
			$this->assertTrue($file->isExists());

			$file->copyTo($copy);
			$this->assertSame($copy->getContent(), $file->getContent());
			$this->assertTrue($file->isExists());

			$content = $file->getContent();

			$file->moveTo($dest);
			$this->assertFalse($file->isExists());
			$this->assertTrue($dest->isExists());
			$this->assertSame($content, $dest->getContent());

			$dest->delete();
			$this->assertFalse($dest->isExists());
		}
	}
?>