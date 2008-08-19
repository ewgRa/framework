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
		
		public function addController($controller)
		{
			$this->controllers[] = $controller;
		}
		
		public function loadControllers($pageId)
		{
			$this->controllers = array();
			$controllers = $this->getPageControllers($pageId);
			
			foreach($controllers as $controller)
			{
				$controllerInstance = new $controller['name'];
				
				if(is_null($controller['module_settings']))
					$controller['module_settings'] = array();
				else $controller['module_settings'] = unserialize($controller['module_settings']);

				$controllerInstance->
					importSettings($controller['module_settings'])->
					setSectionId($controller['section_id'])->
					setPositionInSection($controller['position_in_section'])->
					setViewFileId($controller['view_file_id']);
					
				$this->addController($controllerInstance);
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
				ORDER BY load_priority IS NULL, load_priority
			";
			
			$dbResult = Database::me()->query($dbQuery, array($pageId));

			return Database::me()->resourceToArray($dbResult);
		}
		
		public function render()
		{
			$model = array();
			
			foreach($this->getControllers() as $controller)
			{
				$model[] = array(
					'data' => $controller->getRenderedModel(),
					'section' => $controller->getSectionId(),
					'position' => $controller->getPositionInSection()
				);
			}

			return
				ModelAndView::create()->
					setModel($model)->
					setView(View::createByFileId(Page::me()->getLayoutFileId()))->
					render();
		}
	}
?>