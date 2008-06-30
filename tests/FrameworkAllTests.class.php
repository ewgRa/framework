<?php
	/* $Id$ */

	class FrameworkAllTests extends GroupTest
	{
		private $testDirs = array(
			'patterns',
			'exceptions',
			'core',
			'core/cache',
			'core/localizer',
			'utils',
			'database'
		);
		
		function __construct()
		{
			foreach($this->testDirs as $dir)
			{
				$allTestsFile = glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . $dir
					. DIRECTORY_SEPARATOR . 'All*Tests.class.php');
				
				foreach($allTestsFile as $testFile)
				{
					$this->addTestFile(
						$testFile
					);
				}
			}
		}
		
		public static function deleteDir($dir)
		{
			$files = glob($dir . DIRECTORY_SEPARATOR . '*');
			foreach ($files as $file)
			{
				if(is_dir($file))
				{
					self::deleteDir($file);
				}
				elseif(is_file($file))
				{
					unlink($file);
				}
			}
			rmdir($dir);
		}
	}
?>