<?php
	class Database extends Singleton
	{
		const YAML_DATABASE_FILE_NAME = 'database.yml';

		private $tables = array();
		private $connector = null;
		private $cacheRealization	= null;
		private $mergeYAMLSections = array();
		
		/**
		 * @return Database
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getMergeYAMLSections()
		{
			return $this->mergeYAMLSections;
		}
		
		public function setMergeYAMLSections(array $sections)
		{
			$this->mergeYAMLSections = $sections;
			return $this;
		}
		
		public function initialize($yamlFile)
		{
			$settings = $this->loadCache($yamlFile);
			
			if(!$settings)
			{
				$yamlSettings = YAML::load($yamlFile);
	
				$settings = Arrays::recursiveMergeByArrayKeys(
					$yamlSettings,
					$this->getMergeYAMLSections()
				);
				
				$this->saveCache($yamlFile, $settings);
			}
			
			if(isset($settings['connector']))
			{
				$connector = null;
				
				switch($settings['connector'])
				{
					case 'Mysql':
						$connector = MysqlDatabaseConnector::create();
					break;
				}
				
				$this->setConnector($connector);
			}
			
			if(isset($settings['host']))
				$this->getConnector()->setHost($settings['host']);
			
			if(isset($settings['user']))
				$this->getConnector()->setUser($settings['user']);

			if(isset($settings['password']))
				$this->getConnector()->setPassword($settings['password']);

			if(isset($settings['database']))
				$this->getConnector()->setDatabaseName($settings['database']);

			if(isset($settings['charset']))
				$this->getConnector()->setCharset($settings['charset']);

			if(isset($settings['tableAliases']))
				$this->setTables($settings['tableAliases']);
				
			return $this;
		}
		
		public function setConnector($connector)
		{
			$this->connector = $connector;
			return $this;
		}
		
		public function getConnector()
		{
			return $this->connector;
		}
		
		public function getTable($alias)
		{
			$result = null;
			
			if(isset($this->tables[$alias])) $result = $this->tables[$alias];

			return $result;
		}
		
		public function setTables($tables)
		{
			$this->tables = $tables;
		}
		
		
		public function query($query, $values = array())
		{
			if(!$this->getConnector()->isConnected())
			{
				$this->getConnector()->
					connect()->
					selectDatabase()->
					selectCharset();
			}
			
			return $this->getConnector()->query($query, $values);
		}

		public function fetchArray($dbResult)
		{
			return $this->getConnector()->fetchArray($dbResult);
		}

		public function recordCount($dbResult)
		{
			return $this->getConnector()->recordCount($dbResult);
		}
		
		public function resourceToArray($dbResult)
		{
			return $this->getConnector()->resourceToArray($dbResult);
		}
		
		public function setCacheRealization($realization)
		{
			$this->cacheRealization = $realization;
			return $this;
		}
		
		public function getCacheRealization()
		{
			return $this->cacheRealization;
		}
		
		private function loadCache($yamlFile)
		{
			Assert::fileExists($yamlFile);
			
			$settings = null;
			
			if($this->getCacheRealization())
			{
				$settings = $this->getCacheRealization()->
					getData(
						$yamlFile,
						'site/yaml/database',
						file_exists($yamlFile) ? filemtime($yamlFile) : null
					);
			}
			
			return $settings;
		}
		
		private function saveCache($yamlFile, $cacheData)
		{
			Assert::fileExists($yamlFile);
			
			if(
				$this->getCacheRealization()
				&& $this->getCacheRealization()->isExpired()
			)
			{
				$this->getCacheRealization()->
					setData($cacheData, filemtime($yamlFile), $yamlFile, 'yaml/database');
			}
			
			return $this;
		}
	}
?>