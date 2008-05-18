<?php
	abstract class AllDirectoryTests extends TestSuite
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
				$this->addFile($testFile);
			}
		}
		
		public function __construct()
		{
			$this->TestSuite($this->testTitle);
			$this->addFiles();
		}
	}
?>