<?php
	namespace ewgraFramework {
	
		define('FRAMEWORK_DIR', dirname(__FILE__).'/..');
		define('META_BUILDER_DIR', dirname(__FILE__));
		
		require_once(FRAMEWORK_DIR . '/core/patterns/SingletonInterface.class.php');
		require_once(FRAMEWORK_DIR . '/core/patterns/Singleton.class.php');
		require_once(FRAMEWORK_DIR . '/ClassesAutoloader.class.php');
	
		ClassesAutoloader::me()->addSearchDirectory(FRAMEWORK_DIR, 'ewgraFramework');
	}
	
	namespace {
		function __autoload($className)
		{
			if (!class_exists('ewgraFramework\ClassesAutoloader', false))
				return null;
			
			ewgraFramework\ClassesAutoloader::me()->load($className);
		}
	}
?>