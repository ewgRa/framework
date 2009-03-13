<?php
	/* $Id$ */

	if(!class_exists('Spyc') && defined('LIB_DIR'))
		require_once(
			join(DIRECTORY_SEPARATOR, array(LIB_DIR, 'php', 'spyc', 'spyc.php'))
		);
	
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
			{
				throw
					ExceptionsMapper::me()->createException('File')->
						setCode(FileException::FILE_NOT_EXISTS)->
						setFilePath($file);
			}
			
			return Spyc::YAMLLoad($file);
		}

		public static function loadString($string)
		{
			return Spyc::YAMLLoad($string);
		}
	}
?>