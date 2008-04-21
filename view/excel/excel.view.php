<?php
	class ExcelView extends BaseView
	{
		function Process( $Data ) 
		{
			$this->Result = $Data;
		}
		
		function ShutDown()
		{
			$Data = $this->Result;
			$Page = Registry::Get( 'Page' );
			include_once( SITE_DIR . '/' . $Page->GetLayoutFileProvider() );
		}
	}
?>
