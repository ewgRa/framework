<?php
	/* $Id$ */

	class YAMLTest extends UnitTestCase
	{
		function testLoad()
		{
			$array = Spyc::YAMLLoad( dirname(__FILE__) . '/yaml.test.yml' );
			
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