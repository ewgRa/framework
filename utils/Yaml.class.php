<?php
	/* $Id$ */

	if(!class_exists('Spyc') && defined('LIB_DIR'))
		require_once(LIB_DIR . '/php/spyc/spyc.php');
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
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