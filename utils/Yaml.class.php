<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Yaml
	{
		/**
		 * @example ../tests/utils/YamlTest.class.php
		 */
		public static function load($file)
		{
			if(!file_exists($file))
				throw FileException::fileNotExists()->setFilePath($file);
			
			return Spyc::YAMLLoad($file);
		}

		public static function loadString($string)
		{
			return Spyc::YAMLLoad($string);
		}
	}
?>