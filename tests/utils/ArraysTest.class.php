<?php
	class ArraysTest extends UnitTestCase 
	{
		public $array = null;
		
		public function setUp()
		{
			$this->array = array(
				'all' => array(
					'test' => rand(),
					'test2' => rand()
				),
				'section' => array(
					'test3' => rand(),
					'test2' => rand()
				)
			);
		}
			
		function testRecursiveMerge()
		{
			$this->assertEqual(
				Arrays::recursiveMerge(
					$this->array['all'],
					$this->array['section']
				),
				array(
					'test' => $this->array['all']['test'],
					'test2' => $this->array['section']['test2'],
					'test3' => $this->array['section']['test3']
				)
			);
		}
		
		function testRecursiveMergeByArrayKeys()
		{
			$this->assertEqual(
				Arrays::recursiveMergeByArrayKeys(
					$this->array,
					array('all', 'section')
				),
				array(
					'test' => $this->array['all']['test'],
					'test2' => $this->array['section']['test2'],
					'test3' => $this->array['section']['test3']
				)
			);
		}		
	}
?>