<?php
	class Config extends Singleton
	{
		private $options = null;
		private $mergeYAMLSections = array();
		private $cacheRealization	= null;
		
		private static $instance = null;
		
		/**
		 * @return Config
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__, self::$instance);
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
		
		public function initialize($yamlFile)
		{
			$this->options = $this->loadCache($yamlFile);

			if(!$this->options)
			{
				$yamlSettings = YAML::load($yamlFile);
	
				$settings = Arrays::recursiveMergeByArrayKeys(
					$yamlSettings,
					$this->mergeYAMLSections
				);
				
				foreach($settings as $optionaAlias => $optionValue)
				{
					$this->setOption(
						$optionaAlias,
						$this->replaceVariables($optionValue)
					);
				}
			
				$this->saveCache($yamlFile);
			}
			
			return $this;
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
		
		// FIXME: wtf (use only in delpress)?
		public function setCookieDomain()
		{
			if(!$this->getOption('cookieDomain'))
			{
				$domain = str_replace( 'www.', '', $_SERVER['HTTP_HOST'] );
				$this->setOption('cookieDomain', $domain);
			}
			
			$cookieParams = session_get_cookie_params();
			session_set_cookie_params (
				$cookieParams['lifetime'],
				$cookieParams['path'],
				$this->getOption('cookieDomain'),
				$cookieParams['secure']
			);
			
			return $this;
		}
		
		public function setOption($alias, $value)
		{
			$this->options[$alias] = $value;
			return $this;
		}

		public function getOption($alias)
		{
			$result = null;

			if(isset($this->options[$alias]))
			{
				$result = $this->options[$alias];
			}
			
			return $result;
		}

		public function replaceVariables($variable)
		{
			if(is_array($variable))
			{
				foreach($variable as &$var)
				{
					$var = $this->replaceVariables($var);
				}
			}
			else
			{
				$matches = null;
				preg_match_all( '/%(.*?)%/', $variable, $matches );
				
				foreach(array_unique($matches[1]) as $match)
				{
					$matchVarValue = Variables::getValueByString($match);
					
					if($matchVarValue)
					{
						$variable = str_replace(
							"%" . $match . "%",
							$matchVarValue,
							$variable
						);
					}
				}
			}
			
			return $variable;
		}

		private function loadCache($yamlFile)
		{
			$settings = null;
			
			if($this->getCacheRealization())
			{
				$settings = $this->getCacheRealization()->
					getData(
						$yamlFile,
						'site/yaml/config',
						file_exists($yamlFile) ? filemtime($yamlFile) : null
					);
			}
			
			return $settings;
		}
		
		private function saveCache($yamlFile)
		{
			if(
				$this->getCacheRealization()
				&& $this->getCacheRealization()->isExpired()
			)
			{
				$this->getCacheRealization()->
					setData($this->options, filemtime($yamlFile), $yamlFile, 'site/yaml/config');
			}
			
			return $this;
		}
	}
?>
