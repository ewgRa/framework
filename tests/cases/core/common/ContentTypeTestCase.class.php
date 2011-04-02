<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ContentTypeTestCase extends FrameworkTestCase
	{
		public function testCreateByName()
		{
			$this->assertEquals(
				\ewgraFramework\ContentType::create(
					\ewgraFramework\ContentType::APPLICATION_PHP
				),
				\ewgraFramework\ContentType::createByName('application/php')
			);
		}

		public function testCreateByExtension()
		{
			$this->assertEquals(
				\ewgraFramework\ContentType::create(
					\ewgraFramework\ContentType::APPLICATION_PHP
				),
				\ewgraFramework\ContentType::createByExtension('php')
			);
		}

		public function testFileExtension()
		{
			$this->assertEquals(
				'php',
				\ewgraFramework\ContentType::create(
					\ewgraFramework\ContentType::APPLICATION_PHP
				)->
				getFileExtension()
			);
		}

		public function testCanBeJoined()
		{
			$this->assertTrue(
				\ewgraFramework\ContentType::create(
					\ewgraFramework\ContentType::TEXT_CSS
				)->
				canBeJoined()
			);

			$this->assertFalse(
				\ewgraFramework\ContentType::create(
					\ewgraFramework\ContentType::APPLICATION_PHP
				)->
				canBeJoined()
			);
		}
	}
?>