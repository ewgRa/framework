<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class StringUtils
	{
		public static function upperKeyFirstAlpha($string)
		{
			return ucfirst($string);
		}

		public static function separateByUpperKey($string)
		{
			$string = preg_replace('/([A-Z])/', "_$1", $string);
			
			return mb_strtolower($string);
		}

		public static function getLength($string)
		{
			return mb_strlen($string, 'utf8');
		}

		public static function toLower($string)
		{
			return mb_strtolower($string);
		}
	}
?>