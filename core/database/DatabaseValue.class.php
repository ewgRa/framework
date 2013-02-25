<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseValue
	{
		private $escapeNeeded = true;
		private $quoteNeeded = true;

		private $rawValue = null;

		public static function create($rawValue)
		{
			return new self($rawValue);
		}

		public function __construct($rawValue)
		{
			$this->rawValue = $rawValue;
		}

		public function getRawValue()
		{
			return $this->rawValue;
		}

		public function isEscapeNeeded()
		{
			return $this->escapeNeeded;
		}

		public function isQuoteNeeded()
		{
			return $this->quoteNeeded;
		}

		public function supressQuote()
		{
			$this->quoteNeeded = false;
			return $this;
		}
	}
?>