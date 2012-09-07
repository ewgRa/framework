<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * FIXME: Event class for $event?
	*/
	abstract class Observable implements ObservableInterface
	{
		private $observers = array();

		public function addObserver($event, $callback)
		{
			if (!isset($this->observers[$event]))
				$this->observers[$event] = array();

			$hash = uniqid();

			$this->observers[$event][$hash] = $callback;
			return $hash;
		}

		public function hasObserver($hash)
		{
			foreach ($this->observers as $observers) {
				foreach ($observers as $observerHash => $observer) {
					if ($observerHash == $hash)
						return true;
				}
			}

			return false;
		}

		public function removeObserver($hash)
		{
			throw UnimplementedCodeException::create();
		}

		protected function notifyObservers($event, Model $model)
		{
			if (!isset($this->observers[$event]))
				return $this;

			foreach ($this->observers[$event] as $hash => $callback)
				call_user_func($callback, $model, $this, $hash);

			return $this;
		}
	}
?>