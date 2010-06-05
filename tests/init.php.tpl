<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/

	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', true);
	
	define('PROJECT', 'ewgraFrameworkTests');
	define('FRAMEWORK_DIR', dirname(__FILE__).'/..');
	
	define('EWGRA_PROJECTS_DIR', '/home/www/ewgraProjects');
	define('LIB_DIR', EWGRA_PROJECTS_DIR.'/lib');
		
	define('CASES_DIR', FRAMEWORK_DIR.'/tests/cases');
	define('TMP_DIR', '/tmp/ewgraFrameworkTests');
	define('CACHE_DIR', '/tmp/ewgraFrameworkTests/cache');
	
	define('MEMCACHED_TEST_HOST', 'localhost');
	define('MEMCACHED_TEST_PORT', '11211');

	define('MYSQL_TEST_HOST', 'localhost');
	define('MYSQL_TEST_DATABASE', 'frameworkTest');
	define('MYSQL_TEST_USER', '');
	define('MYSQL_TEST_PASSWORD', '');
	define('MYSQL_TEST_CHARSET', 'utf8');
	
	require_once(FRAMEWORK_DIR.'/core/common/Dir.class.php');
	require_once(FRAMEWORK_DIR.'/core/common/File.class.php');
	
	$dir = Dir::create()->setPath(TMP_DIR);
	
	if (!$dir->isExists())
		$dir->make();
	
	$dir = Dir::create()->setPath(CACHE_DIR);
	
	if (!$dir->isExists())
		$dir->make();
	
	classesAutoloaderInit();
	cacheInit();
	
	function classesAutoloaderInit()
	{
		require_once(FRAMEWORK_DIR.'/core/patterns/SingletonInterface.class.php');
		require_once(FRAMEWORK_DIR.'/core/patterns/Singleton.class.php');
		require_once(FRAMEWORK_DIR.'/ClassesAutoloader.class.php');

		$foundClassesFile =
			File::create()->
			setPath(
				CACHE_DIR.'/'.PROJECT.'-classesAutoloader-'.md5(__FILE__)
			);
		
		ClassesAutoloader::me()->setFoundClasses(
			$foundClassesFile->isExists()
				? unserialize($foundClassesFile->getContent())
				: array()
		);

		foreach (glob(FRAMEWORK_DIR.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR) as $dir) {
			if (realpath($dir) != realpath(dirname(__FILE__)))
				ClassesAutoloader::me()->addSearchDirectories(array($dir));
		}

		ClassesAutoloader::me()->loadAllClasses();
		ClassesAutoloader::me()->addSearchDirectories(array(dirname(__FILE__)));
		
		register_shutdown_function('storeAutoloaderMap', $foundClassesFile);
	}
	
	function cacheInit()
	{
	}
	
	function storeAutoloaderMap(File $file)
	{
		if (ClassesAutoloader::me()->isClassMapChanged()) {
			$file->setContent(
				serialize(ClassesAutoloader::me()->getFoundClasses())
			);
		}
	}
	
	function __autoload($className)
	{
		if (!class_exists('ClassesAutoloader', false))
			return null;
		
		ClassesAutoloader::me()->load($className);
	}
?>