<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Yaml
	{
		public static function load($file)
		{
			if(!file_exists($file))
				throw FileException::fileNotExists()->setFilePath($file);

			self::checkInclude();
			
			return Spyc::YAMLLoad($file);
		}

		public static function loadString($string)
		{
			self::checkInclude();
			
			return Spyc::YAMLLoad($string);
		}
		
		public static function save($file,$data)
		{
			self::checkInclude();
			$spyc = new Spyc;
			
			return file_put_contents($file, $spyc->dump($data));
		}
		
		private static function checkInclude()
		{
			if (!class_exists('Spyc') && defined('LIB_DIR'))
				require_once(LIB_DIR . '/php/spyc/spyc.php');
		}
	}
?>