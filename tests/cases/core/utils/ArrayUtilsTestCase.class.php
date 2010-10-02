<?php
	namespace ewgraFramework\tests;
	
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
				array(
					'test' => $this->array['all']['test'],
					'test2' => $this->array['section']['test2'],
					'test3' => $this->array['section']['test3']
				),
				\ewgraFramework\ArrayUtils::recursiveMerge(
					$this->array['all'],
					$this->array['section']
				)
			);
		}

		public function testGetObjectIds()
		{
			$object = new ArrayUtilsTestObject();
			
			$this->assertEquals(
				array($object->getId()),
				\ewgraFramework\ArrayUtils::getObjectIds(array($object))
			);
		}
	}
?>