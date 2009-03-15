<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * // FIXME: tested?
	*/
	final class ModuleDispatcher extends Module
	{
		/**
		 * @var ModuleDispatcherDA
		 */
		private $da = null;
		
		/**
		 * @var ModuleDispatcherCacheWorker
		 */
		private $cacheWorker = null;
		
		private $modules = array();
		
		/**
		 * @return ModuleDispatchers
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return ModuleDispatcherDA
		 */
		public function da()
		{
			if(!$this->da)
				$this->da = ModuleDispatcherDA::create();
			
			return $this->da;
		}
		
		/**
		 * @return ModuleDispatcherCacheWorker
		 */
		public function cacheWorker()
		{
			if(!$this->cacheWorker)
				$this->cacheWorker = ModuleDispatcherCacheWorker::create()->
					setModule($this);

			return $this->cacheWorker;
		}
		
		public function getModules()
		{
			return $this->modules;
		}
		
		/**
		 * @return ModuleDispatcher
		 */
		public function addModule($module, $section, $position)
		{
			$this->modules[] = array(
				'instance'	=> $module,
				'section'	=> $section,
				'position'	=> $position
			);
			
			return $this;
		}
		
		/**
		 * @return ModuleDispatcher
		 */
		public function loadModules()
		{
			$page = $this->getRequest()->getAttached(AttachedAliases::PAGE);
			$this->modules = array();
			$modules = $this->getPageModules($page);
			
			foreach($modules as $module)
			{
				$moduleInstance = new $module['name'];
				$moduleInstance->setRequest($this->getRequest());
				
				$module['module_settings'] =
					is_null($module['module_settings'])
						? array()
						: unserialize($module['module_settings']);

				if(!is_null($module['settings']))
				{
					$module['module_settings'] =
						array_merge(
							$module['module_settings'],
							unserialize($module['settings'])
						);
				}
				
				$moduleInstance->
					importSettings($module['module_settings'])->
					setView(
						$module['view_file_id']
							? ViewFactory::createByFileId(
								$module['view_file_id']
							)
							: null
					);
					
				$this->addModule(
					$moduleInstance,
					$module['section_id'],
					$module['position_in_section']
				);
			}

			return $this;
		}
		
		private function getPageModules(Page $page)
		{
			$result = null;
			
			if($cacheTicket = $this->cacheWorker()->createTicket())
				$cacheTicket->restoreData();
			
			if(!$cacheTicket || $cacheTicket->isExpired())
			{
				$result = $this->da()->getPageModules($page->getId());
				
				if($cacheTicket)
					$cacheTicket->setData($result)->storeData();
			}
			else
				$result = $cacheTicket->getData();
				
			return $result;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$result = Model::create();
			
			foreach($this->getModules() as $module)
			{
				$result->append(
					array(
						'data' =>
							$module['instance']->getRenderedModel(),
						'section' => $module['section'],
						'position' => $module['position']
					)
				);
			}
			
			return $result;
		}
	}
?>