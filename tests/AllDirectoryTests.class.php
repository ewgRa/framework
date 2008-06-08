<?php
	class AllDirectoryTests extends GroupTest
	{
		protected $testTitle = "All tests";
		protected $thisFile = __FILE__;
		
		function addFiles()
		{
			foreach(
				glob(dirname($this->thisFile) . DIRECTORY_SEPARATOR . '*Test.class.php')
				as $testFile
			)
			{
				$this->addTestFile($testFile);
			}
		}
		
		public function __construct()
		{
			$this->GroupTest($this->testTitle);
			$this->addFiles();
		}
	}
?>