<?php
	namespace ewgraFramework\tests;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveUploadFileTestCase extends FrameworkTestCase
	{
		public function testImport()
		{
			$file = array(
				'error' => 4,
				'tmp_name' => '/tmp/fwef',
				'name' => 'aaaa.jpg'
			);

			$primitive =
				\ewgraFramework\PrimitiveUploadFile::create('testPrimitive')->
				import(array('testPrimitive' => $file));
			
			$this->assertSame(
				array($primitive::UPLOAD_ERROR),
				$primitive->getErrors()
			);
		}

		public function testImportMissing()
		{
			$file = array('error' => 0, 'tmp_name' => '');

			$primitive =
				\ewgraFramework\PrimitiveUploadFile::create('testPrimitive')->
				setRequired()->
				import(array('testPrimitive' => $file));
			
			$this->assertSame(
				array($primitive::MISSING_ERROR),
				$primitive->getErrors()
			);
		}

		public function testSuccessImport()
		{
			$file = array(
				'error' => 0,
				'tmp_name' => '/tmp/fwef',
				'name' => 'aaaa.jpg'
			);

			$primitive =
				\ewgraFramework\PrimitiveUploadFile::create('testPrimitive')->
				import(array('testPrimitive' => $file));
			
			$this->assertSame(
				'/tmp/fwef',
				$primitive->getFile()->getPath()
			);
		}
	}
?>