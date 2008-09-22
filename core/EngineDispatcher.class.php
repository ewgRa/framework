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
		
		public function normalizeRequest()
		{
			if(function_exists('set_magic_quotes_runtime'))
			{
				set_magic_quotes_runtime(0);
			}
			
			if(function_exists( 'get_magic_quotes_gpc') && get_magic_quotes_gpc())
			{
				$this->
					strips($_GET)->
					strips($_POST)->
					strips($_COOKIE)->
					strips($_REQUEST);
				
				if(isset($_SERVER['PHP_AUTH_USER']))
					$this->strips($_SERVER['PHP_AUTH_USER']);

				if(isset($_SERVER['PHP_AUTH_PW']))
					$this->strips($_SERVER['PHP_AUTH_PW']);
			}
			
			return $this;
		}

		public function prepareStart()
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
			
			Session::me()->relativeStart();
			
			if(Session::me()->isStarted())
				User::me()->onSessionStarted();
			
			return $this;
		}
		
		private function getPagePathMapper()
		{
			$result = null;
			
			$cacheTicket = Cache::me()->createTicket('pagePathMapper')->
				restoreData();

			if($cacheTicket->isExpired())
			{
				$result = PagePathMapper::create()->loadMap();

				$cacheTicket->setData($result)->storeData();
			}
			else
				$result = $cacheTicket->getData();
				
			return $result;
		}
		
		private function loadPage($pageId)
		{
			$cacheTicket = Cache::me()->createTicket('page')->
				setKey($pageId)->
				restoreData();
			
			if($cacheTicket->isExpired())
			{
				Page::create($pageId);
				$cacheTicket->setData(Page::me())->storeData();
			}
			else
				Singleton::setInstance('Page', $cacheTicket->getData());

			return $this;
		}
		
		public function start()
		{
			Localizer::me()->defineLanguage();
			
			$pageId = $this->getPagePathMapper()->getPageId(
				UrlHelper::me()->getEnginePagePath()
			);
			
			if(!$pageId)
			{
				throw
					ExceptionsMapper::me()->createException('Page')->
						setCode(PageException::PAGE_NOT_FOUND)->
						setUrl(UrlHelper::me()->getEnginePagePath());
			}
			
			$this->loadPage($pageId);
			
			Page::me()->
				setRequestPath(UrlHelper::me()->getEnginePagePath())->
				processPath();
			
			if(Page::me()->getViewType() == View::AJAX)
				JsHttpRequest::initialize(Config::me()->getOption('charset'));
			
			Page::me()->checkAccessPage(User::me()->getRights());
			
			$cacheTicket = Cache::me()->createTicket('controllerDispatcher')->
				setKey(Page::me()->getId())->
				restoreData();
			
			if($cacheTicket->isExpired())
			{
				ControllerDispatcher::me()->loadControllers(Page::me()->getId());
				$cacheTicket->setData(ControllerDispatcher::me())->storeData();
			}
			else
				Singleton::setInstance(
					'ControllerDispatcher',
					$cacheTicket->getData()
				);
			
			return $this;
		}
		
		public function render()
		{
			$this->renderedOutput = ControllerDispatcher::me()->render();
			return $this;
		}
		
		private function strips(&$el)
		{
			if(is_array($el))
			{
				foreach($el as &$v)
					$this->strips($v);
			}
			else
				$el = stripslashes($el);
				
			return $this;
			
		}
		
		public function redirectToUri($uri)
		{
			Localizer::me()->setPath($uri);

			return EngineDispatcher::me()->
				start()->
				render();
		}
	}
?>