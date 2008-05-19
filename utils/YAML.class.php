<?php
	require_once(LIB_DIR . '/php/spyc/spyc.php');
	
	class YAML
	{
		public static function load($file)
		{
			$yamlFile = realpath($file);
			
			if(!$yamlFile)
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