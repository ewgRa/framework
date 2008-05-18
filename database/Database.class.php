<?php
	// TODO: cache!!!
	class Database extends Singleton
	{
		const YAML_DATABASE_FILE_NAME = 'database.yml';

		private $tables = array();
		private $connector = null;
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
			$yamlSettings = YAML::load($yamlFile);

			$settings = Arrays::recursiveMergeByArrayKeys(
				$yamlSettings,
				$this->getMergeYAMLSections()
			);
			
			if(isset($settings['host']))
				$this->setHost($settings['host']);
			
			if(isset($settings['user']))
				$this->setUser($settings['user']);

			if(isset($settings['password']))
				$this->setPassword($settings['password']);

			if(isset($settings['database']))
				$this->setDatabase($settings['database']);

			if(isset($settings['charset']))
				$this->setCharset($settings['charset']);

			if(isset($settings['tables']))
				$this->setTables($settings['tables']);
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
	}
?>