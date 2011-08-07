<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/

	require_once(dirname(__FILE__).'/../init.php');

	\PHPUnit_Util_Filter::addDirectoryToFilter(
		FRAMEWORK_DIR.'/incubator', '.php'
	);

	\PHPUnit_Util_Filter::addDirectoryToFilter(
		\ewgraFramework\LIB_DIR, '.php'
	);

	require_once(dirname(__FILE__).'/FrameworkTestCase.class.php');
?>