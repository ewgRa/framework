<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ActionChainControllerTestCase extends FrameworkTestCase
	{
		public function testActionScopeKey()
		{
			$chain = new TestActionChainController1();

			$this->assertSame(
				'ewgraFrameworktestsTestActionChainController1Action',
				TestActionChainController1::getActionScopeKey()
			);
		}
	}

	final class TestActionChainController1 extends \ewgraFramework\ActionChainController
	{
	}
?>