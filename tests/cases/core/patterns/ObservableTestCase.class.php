<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	use ewgraFramework\Observable;

	final class ObservableTestCase extends FrameworkTestCase
	{
		public function testNotify()
		{
			$observer = new ObserverTest();

			$observable = new ObservableTest();

			$hash =
				$observable->addObserver(
					ObservableTest::EVENT,
					array($observer, 'eventFired')
				);

			$observable->notify();

			$this->assertSame(
				array($hash, 'val'),
				$observer->getTestVar()
			);

			$this->assertTrue($observable->hasObserver($hash));
			$this->assertFalse($observable->hasObserver($hash.'1'));
		}

		public function testRemoveObserver()
		{
			$observer = new ObserverTest();

			$observable = new ObservableTest();

			$hash =
				$observable->addObserver(
					ObservableTest::EVENT,
					array($observer, 'eventFired')
				);

			try {
				$observable->removeObserver($hash);
				$this->fail();
			} catch (\ewgraFramework\UnimplementedCodeException $e) {
				# good
			}
		}
	}

	final class ObservableTest extends \ewgraFramework\Observable
	{
		const EVENT = 'testEvent';

		public function notify()
		{
			return
				$this->notifyObservers(
					self::EVENT,
					\ewgraFramework\Model::create()->
					set('var', 'val')
				);
		}
	}

	final class ObserverTest
	{
		private $testVar = null;

		public function eventFired(
			\ewgraFramework\Model $model,
			\ewgraFramework\Observable $testObservable,
			$addObserverHash
		) {
			$this->testVar = array(
				$addObserverHash,
				$model->get('var')
			);
		}

		public function getTestVar()
		{
			return $this->testVar;
		}
	}
?>