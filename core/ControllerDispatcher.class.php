<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
	*/
	class ControllerDispatcher extends Singleton
	{
		/**
		 * @var ControllerDispatcherDA
		 */
		private $da = null;
		
		private $controllers = array();
		
		/**
		 * @return ControllerDispatchers
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		/**
		 * @return ControllerDispatcherDA
		 */
		public function da()
		{
			if(!$this->da)
				$this->da = ControllerDispatcherDA::create();
			
			return $this->da;
		}
		
		public function getControllers()
		{
			return $this->controllers;
		}
		
		/**
		 * @return ControllerDispatcher
		 */
		public function addController($controller, $section, $position)
		{
			$this->controllers[] = array(
				'instance'	=> $controller,
				'section'	=> $section,
				'position'	=> $position
			);
			
			return $this;
		}
		
		/**
		 * @return ControllerDispatcher
		 */
		public function loadControllers(HttpRequest $request)
		{
			$page = $request->getAttached(AttachedAliases::PAGE);
			$this->controllers = array();
			$controllers = $this->getPageControllers($page);
			
			foreach($controllers as $controller)
			{
				$controllerInstance = new $controller['name'];
				
				$controller['controller_settings'] =
					is_null($controller['controller_settings'])
						? array()
						: unserialize($controller['controller_settings']);

				if(!is_null($controller['settings']))
				{
					$controller['controller_settings'] =
						array_merge(
							$controller['controller_settings'],
							unserialize($controller['settings'])
						);
				}
				
				$controllerInstance->
					importSettings($request, $controller['controller_settings'])->
					setView(
						$controller['view_file_id']
							? ViewFactory::createByFileId(
								$controller['view_file_id']
							)
							: null
					);
					
				$this->addController(
					$controllerInstance,
					$controller['section_id'],
					$controller['position_in_section']
				);
			}

			return $this;
		}
		
		private function getPageControllers(Page $page)
		{
			$result = null;
			
			try {
				$cacheTicket = Cache::me()->createTicket('controllerDispatcher')->
					setKey($page->getId())->
					restoreData();
			}
			catch(MissingArgumentException $e) {
				$cacheTicket = null;
			}
			
			if(!$cacheTicket || $cacheTicket->isExpired())
			{
				$result = $this->da()->getPageControllers($page->getId());
				
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
		public function getModel(HttpRequest $request)
		{
			$result = Model::create();
			
			foreach($this->getControllers() as $controller)
			{
				$result->append(
					array(
						'data' =>
							$controller['instance']->getRenderedModel($request),
						'section' => $controller['section'],
						'position' => $controller['position']
					)
				);
			}
			
			return $result;
		}
	}
?>