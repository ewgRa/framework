<?php
	/* $Id$ */

	// FIXME: tested?
	class ControllerDispatcher extends Singleton
	{
		private $controllers = array();
		
		/**
		 * @return ModuleDispatcher
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getControllers()
		{
			return $this->controllers;
		}
		
		public function addController($controller, $section, $position)
		{
			$this->controllers[] = array(
				'instance' => $controller,
				'section'	 => $section,
				'position'	 => $position
			);
		}
		
		public function loadControllers($pageId)
		{
			$this->controllers = array();
			$controllers = $this->getPageControllers($pageId);
			
			foreach($controllers as $controller)
			{
				$controllerInstance = new $controller['name'];
				
				$controller['module_settings'] =
					is_null($controller['module_settings'])
						? array()
						: unserialize($controller['module_settings']);

				$controllerInstance->
					importSettings($controller['module_settings'])->
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

			return true;
		}
		
		private function getPageControllers($pageId)
		{
			$dbQuery = "
				SELECT
					t1.*, t2.section_id, t2.position_in_section, t2.module_settings,
					t2.view_file_id
				FROM " . Database::me()->getTable('Controllers') . " t1
				INNER JOIN " . Database::me()->getTable('PagesControllers_ref') . " t2
					ON( t1.id = t2.controller_id AND t2.page_id = ? )
				ORDER BY load_priority, load_priority IS NULL
			";
			
			$dbResult = Database::me()->query($dbQuery, array($pageId));

			return Database::me()->resourceToArray($dbResult);
		}
		
		public function getModel()
		{
			$result = array();
			
			foreach($this->getControllers() as $controller)
			{
				$result[] = array(
					'data' => $controller['instance']->getRenderedModel(),
					'section' => $controller['section'],
					'position' => $controller['position']
				);
			}
			
			return $result;
		}
	}
?>