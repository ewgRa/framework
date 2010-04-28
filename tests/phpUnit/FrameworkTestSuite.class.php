<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class FrameworkTestSuite extends PHPUnit_Framework_TestSuite
	{
		protected $selfDir = null;
		protected $casesDir = null;
		
		public function getSelfDir()
		{
			if (!$this->selfDir)
				return ROOT_SUITE_DIR;
				
			return $this->selfDir;
		}

		public function setSelfDir($dir)
		{
			$this->selfDir = $dir;
			return $this;
		}
		
		public function getCasesDir()
		{
			if (!$this->casesDir)
				return ROOT_SUITE_CASES_DIR;
				
			return $this->casesDir;
		}

		public function setCasesDir($dir)
		{
			$this->casesDir = $dir;
			return $this;
		}
		
		public function setUp()
		{
			$this->addTestCases();
			$this->addTestSuites();
		}

		protected function addTestCases()
		{
			$this->addTestFiles(
				glob($this->getCasesDir().'/*TestCase.class.php')
			);
			
			return $this;
		}
		
		protected function addTestSuites()
		{
			foreach (glob($this->getSelfDir().'/*', GLOB_ONLYDIR) as $dir) {
				foreach (glob($dir.'/*TestSuite.class.php') as $file) {
					$childSuiteClass = str_replace('.class.php', '', basename($file));
					$childSuite = new $childSuiteClass;

					$childSuite->
						setSelfDir($dir)->
						setCasesDir($this->getCasesDir().'/'.basename($dir));

					$this->addTestSuite($childSuite);
				}
			}
			
			return $this;
		}
	}
?>