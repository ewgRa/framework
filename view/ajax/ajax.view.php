<?php
	class AjaxView extends BaseView
	{
		function Process( $Data ) 
		{
			$this->OutputHeaders();
			
			$JsHttpRequest = JsHttpRequest::getInstance( 'UTF-8' );
			$JsHttpRequest->RESULT = $Data;
		} 

		function Shutdown()
		{
			$JsHttpRequest = JsHttpRequest::getInstance( 'UTF-8' );
			$this->Result = $JsHttpRequest->_obHandler( '' );
			echo $this->Result;
		}
	}
?>
