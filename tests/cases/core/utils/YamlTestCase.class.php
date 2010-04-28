<?php
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

			$yamlFile = File::create()->setPath(TMP_DIR.'/test.yml');
			
			Yaml::save($yamlFile, $data);
			
			$yamlResult = Yaml::load($yamlFile);
			
			$this->assertSame($yamlResult, $data);
			
			$this->assertSame(
				Yaml::loadString($yamlFile->getContent()),
				$data
			);
			
			$yamlFile->delete();
		}
	}
?>