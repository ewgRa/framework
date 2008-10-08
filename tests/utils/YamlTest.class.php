<?php
	/* $Id$ */

	class YamlTest extends UnitTestCase
	{
		public function testLoad()
		{
			$array = Yaml::load(dirname(__FILE__) . '/yaml.test.yml');
			
			$this->assertEqual(
				$array,
				array(
					'all' => array(
						'testArray' => array(
							'arrayKey1'=> 'arrayValue1',
							'arrayKey2' => 'arrayValue2'
						)
					),
					'testSection' => array(
						'testArray' => array(
							'arrayKey2' => 'arrayValue2_testSection'
						)
					)
				)
			);
		}
	}
?>