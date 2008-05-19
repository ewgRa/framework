<?php
	class Database extends Singleton
	{
		const YAML_DATABASE_FILE_NAME = 'database.yml';

		private $tables = array();
		private $connector = null;
		private $cacheConnector	= null;		
		private $mergeYAMLSections = array();
		
		private static $instance = null;

		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
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
			
			if(isset($settings['host']))
				$this->getConnector()->setHost($settings['host']);
			
			if(isset($settings['user']))
				$this->getConnector()->setUser($settings['user']);

			if(isset($settings['password']))
				$this->getConnector()->setPassword($settings['password']);

			if(isset($settings['database']))
				$this->getConnector()->setDatabase($settings['database']);

			if(isset($settings['charset']))
				$this->getConnector()->setCharset($settings['charset']);

			if(isset($settings['tables']))
				$this->setTables($settings['tables']);
				
			return $this;
		}
		
		public function setConnector($connector)
		{
			$this->connector = $connector();
			return $this;
		}
		
		public function getConnector()
		{
			return $this->connector;
		}
		
		public function setTables($tables)
		{
			$this->tables = $tables;	
		}
		
		
		public static function query($query, $values = array())
		{
			return self::me()->getConnector()->query($query, $values);
		}

		public function setCacheConnector($connector)
		{
			$this->cacheConnector = $connector;
			return $this;
		}
		
		public function getCacheConnector()
		{
			return $this->cacheConnector;
		}
		
		private function loadCache($yamlFile)
		{
			$settings = null;
			
			if($this->getCacheConnector())
			{
				$settings = $this->getCacheConnector()->
					getData(
						$yamlFile,
						'yaml/database',
						file_exists($yamlFile) ? filemtime($yamlFile) : null
					);
			}
			
			return $settings;			
		}
		
		private function saveCache($yamlFile, $cacheData)
		{
			if(
				$this->getCacheConnector()
				&& $this->getCacheConnector()->isExpired()
			)
			{
				$this->getCacheConnector()->
					setData($cacheData, filemtime($yamlFile), $yamlFile, 'yaml/database');
			}
			
			return $this;
		}
	}
?>