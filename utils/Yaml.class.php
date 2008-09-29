<?php
	/* $Id$ */

	if(!class_exists('Spyc') && defined('LIB_DIR'))
		require_once(LIB_DIR . '/php/spyc/spyc.php');
	
	class Yaml
	{
		/**
		 * @example ../tests/utils/YamlTest.class.php
		 */
		public static function load($file)
		{
			if(!file_exists($file))
			{
				throw
					ExceptionsMapper::me()->createException('File')->
						setCode(FileException::FILE_NOT_EXISTS)->
						setFilePath($file);
			}
			
			return Spyc::YAMLLoad($file);
		}
	}
?>