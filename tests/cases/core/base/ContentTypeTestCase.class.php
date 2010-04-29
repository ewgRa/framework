<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ContentTypeTestCase extends FrameworkTestCase
	{
		public function testCreateByName()
		{
			$this->assertEquals(
				ContentType::create(ContentType::APPLICATION_PHP),
				ContentType::createByName('application/php')
			);
		}

		public function testCreateByExtension()
		{
			$this->assertEquals(
				ContentType::create(ContentType::APPLICATION_PHP),
				ContentType::createByExtension('php')
			);
		}

		public function testFileExtension()
		{
			$this->assertEquals(
				ContentType::create(ContentType::APPLICATION_PHP)->
				getFileExtension(),
				'php'
			);
		}

		public function testCanBeJoined()
		{
			$this->assertTrue(
				ContentType::create(ContentType::TEXT_CSS)->canBeJoined()
			);

			$this->assertFalse(
				ContentType::create(ContentType::APPLICATION_PHP)->canBeJoined()
			);
		}
	}
?>