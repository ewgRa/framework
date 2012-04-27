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

		public function isWrong($value)
		{
			// FIXME: when fixme in BasePrimitive "we need check isWrong only if value is not empty" will be fixed
			// this check can be removed
			if ($this->isEmpty($value))
				return false;

			return !preg_match(self::MAIL_PATTERN, $value);
		}
	}
?>