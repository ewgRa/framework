<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ArrayUtilsTestCase extends FrameworkTestCase
	{
		private $array = null;
		
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
			
		public function testRecursiveMerge()
		{
			$this->assertEquals(
				ArrayUtils::recursiveMerge(
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

		public function testGetObjectIds()
		{
			$object = new ArrayUtilsTestObject();
			
			$this->assertEquals(
				ArrayUtils::getObjectIds(array($object)),
				array($object->getId())
			);
		}
	}
?>