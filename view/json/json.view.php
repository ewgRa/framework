<?php
	class JsonView extends BaseView
	{
		function Process( $Data ) 
		{
			$this->OutputHeaders();
			$this->Result = json_encode( $Data );
		} 

		function Shutdown()
		{
			echo $this->Result;
		}
	}
?>
