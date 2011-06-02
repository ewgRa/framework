<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveEmail extends PrimitiveString
	{
		const MAIL_PATTERN = '/^[a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+(\.[a-zA-Z0-9\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~]+)*@[a-zA-ZZ0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/';

		/**
		 * @return PrimitiveEmail
		 */
		public static function create($name)
		{
			return new self($name);
		}

		/**
		 * @return PrimitiveEmail
		 */
		public function import($scope)
		{
			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue()) {
				if (!preg_match(self::MAIL_PATTERN, $this->getValue())) {
					$this->markWrong();
					$this->dropValue();
				}
			}

			return $result;
		}
	}
?>