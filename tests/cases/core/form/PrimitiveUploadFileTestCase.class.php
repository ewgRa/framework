<?php
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
				PrimitiveUploadFile::create('testPrimitive')->
				import(array('testPrimitive' => $file));
			
			$this->assertSame(
				$primitive->getErrors(),
				array(PrimitiveErrors::UPLOAD_ERROR)
			);
		}

		public function testImportMissing()
		{
			$file = array('error' => 0, 'tmp_name' => '');

			$primitive =
				PrimitiveUploadFile::create('testPrimitive')->
				setRequired()->
				import(array('testPrimitive' => $file));
			
			$this->assertSame(
				$primitive->getErrors(),
				array(PrimitiveErrors::MISSING)
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
				PrimitiveUploadFile::create('testPrimitive')->
				import(array('testPrimitive' => $file));
			
			$this->assertSame(
				$primitive->getFile()->getPath(),
				'/tmp/fwef'
			);
		}
	}
?>