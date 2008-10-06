<?php
	/* $Id$ */
	
	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * //FIXME: tested?
	*/
	class ExceptionMapperController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest()
		{
			$result = parent::handleRequest();
			
			$this->fillMap();
						
			return $result;
		}
		
		/**
		 * @return ExceptionMapperController
		 */
		private function fillMap()
		{
			$exceptionMap = Config::me()->getOption('exceptionMap');
			
			if($exceptionMap)
			{
				foreach($exceptionMap as $exceptionAlias => $exceptionClassName)
				{
					ExceptionsMapper::me()->setClassName(
						$exceptionAlias, $exceptionClassName
					);
				}
			}
			
			return $this;
		}
	}
?>