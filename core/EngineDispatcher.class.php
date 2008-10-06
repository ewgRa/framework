<?php
	/* $Id$ */
	
	// FIXME: tested?
	class EngineDispatcher extends Singleton
	{
		private $renderedOutput = null;
		
		/**
		 * @return EngineDispatcher
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getRenderedOutput()
		{
			return $this->renderedOutput;
		}
		
		public function setRenderedOutput($rendered)
		{
			$this->renderedOutput = $rendered;
			return $this;
		}
	}
?>