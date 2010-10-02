<?php
	namespace ewgraFramework\tests;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class YamlTestCase extends FrameworkTestCase
	{
		public function testSaveLoad()
		{
			$data =
 				array(
					'test' => 'testValue',
					'testArray' => array(
						0 => array('key' => 'value')
					),
					'testArray2' => array(
						'key' => 'value'
					)
				);

			$yamlFile = \ewgraFramework\File::create()->setPath(TMP_DIR.'/test.yml');
			
			\ewgraFramework\Yaml::save($yamlFile, $data);
			
			$yamlResult = \ewgraFramework\Yaml::load($yamlFile);
			
			$this->assertSame($data, $yamlResult);
			
			$this->assertSame(
				$data,
				\ewgraFramework\Yaml::loadString($yamlFile->getContent())
			);
			
			$yamlFile->delete();
		}
	}
?>