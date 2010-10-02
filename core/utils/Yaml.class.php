<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Yaml
	{
		public static function load(File $file)
		{
			if(!$file->isExists())
				throw FileNotExistsException::create();

			self::checkInclude();
			
			return \Spyc::YAMLLoad($file->getPath());
		}

		public static function loadString($string)
		{
			self::checkInclude();
			
			return \Spyc::YAMLLoad($string);
		}
		
		public static function save(File $file, $data)
		{
			self::checkInclude();
			$spyc = new \Spyc;
			
			return $file->setContent($spyc->dump($data));
		}
		
		private static function checkInclude()
		{
			if (!class_exists('\Spyc') && defined('LIB_DIR'))
				require_once(LIB_DIR.'/php/spyc/spyc.php');
		}
	}
?>