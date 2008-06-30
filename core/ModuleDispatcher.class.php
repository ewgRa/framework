<?php
	/* $Id: $ */

	// FIXME: tested?
	// FIXME: refactoring?
	class ModuleDispatcher
	{
		/**
		 * Загрузить модули
		 * @return boolean
		 */
		function LoadModules()
		{
			$Page = Registry::Get( 'Page' );
			$Modules = $this->GetPageModules( $Page->GetID() );
			
			foreach ( $Modules as $Module )
			{
				$ModuleInstance = new $Module['name'];
				if( is_null( $Module['module_settings'] ) ) $Module['module_settings'] = array();
				else $Module['module_settings'] = unserialize( $Module['module_settings'] );
				$ModuleInstance->ToEngine( $Module['module_settings'], $Module['section_id'], $Module['position_in_section'], $Page->ControllerSettings );
			}
			return true;
		}
		
		/**
		 * Загрузить информацию о модулях, подключенных к данной странице
		 * @param int $PageID - ID страницы в базе
		 * @return array
		 */
		function GetPageModules( $PageID )
		{
        	$Modules = Cache::Get( array( 'Get Page Modules', func_get_args() ), 'engine/module_dispatcher' );
			if( Cache::Expired() )
			{
				$DB = Registry::Get( 'DB' );
				$dbq = "SELECT t1.*, t2.section_id, t2.position_in_section, t2.module_settings FROM " . $DB->TABLES['Modules'] . " t1 INNER JOIN " . $DB->TABLES['PagesModules_ref'] . " t2 ON( t1.id = t2.module_id AND t2.page_id = ? ) ORDER BY load_priority IS NULL, load_priority";
				$dbr = $DB->Query( $dbq, array( $PageID ) );
				$Modules = $DB->ResourceToArray( $dbr );
		        Cache::Set( $Modules, 24*60*60 );
			}
			return $Modules;
		}
	}
?>