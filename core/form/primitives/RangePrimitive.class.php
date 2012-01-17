<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class RangePrimitive extends BasePrimitive
	{
		private $min = null;
		private $max = null;

		abstract public function getRangeValue();

		public function setMin($min)
		{
			$this->min = $min;
			return $this;
		}

		public function hasMin()
		{
			return $this->getMin() !== null;
		}

		public function getMin()
		{
			return $this->min;
		}

		public function setMax($max)
		{
			$this->max = $max;
			return $this;
		}

		public function hasMax()
		{
			return $this->getMax() !== null;
		}

		public function getMax()
		{
			return $this->max;
		}

		/**
		 * @return RangePrimitive
		 */
		public function import($scope)
		{
			$result = parent::import($scope);

			if ($this->hasValue()) {
				$rangeValue = $this->getRangeValue();

				if (
					($this->hasMin() && $rangeValue < $this->getMin())
					|| ($this->hasMax() && $rangeValue > $this->getMax())
				)
					$this->markWrong();
			}

			return $result;
		}
	}
?>