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
			ob_start();
	
			register_shutdown_function(array($this, 'shutdown'));
			
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
		
		private function loadPagePathMapper()
		{
			$cacheTicket = Cache::me()->createTicket('pagePathMapper')->
				restoreData();

			if($cacheTicket->isExpired())
			{
				PagePathMapper::me()->loadMap();
				$cacheTicket->setData(PagePathMapper::me())->storeData();
			}
			else
				Singleton::setInstance('PagePathMapper', $cacheTicket->getData());
				
			return $this;
		}
		
		private function loadPage()
		{
			$pageId = PagePathMapper::me()->getPageId(
				UrlHelper::me()->getEnginePagePath()
			);

			$cacheTicket = Cache::me()->createTicket('page')->
				setKey(
					$pageId ? $pageId : UrlHelper::me()->getEnginePagePath()
				)->
				restoreData();
			
			if($cacheTicket->isExpired())
			{
				Page::create(UrlHelper::me()->getEnginePagePath(), $pageId);
				$cacheTicket->setData(Page::me())->storeData();
			}
			else
				Singleton::setInstance('Page', $cacheTicket->getData());

			return $this;
		}
		
		public function start()
		{
			Localizer::me()->defineLanguage();
			
			// TODO: check cache data for path. if no cache, load page, and then
			// 	 check preg pages
			
			$this->loadPagePathMapper();
			
			$this->loadPage();
			
			Page::me()->
				setRequestPath(UrlHelper::me()->getEnginePagePath())->
				processPath();
			
			if(Page::me()->getViewType() == View::AJAX)
				JsHttpRequest::initialize(Config::me()->getOption('charset'));
			
			Page::me()->checkAccessPage(User::me()->getRights());
			
			// FIXME: cache controller dispatcher?
			ControllerDispatcher::me()->loadControllers(Page::me()->getId());
			
			return $this;
		}
		
		public function render()
		{
			$this->renderedOutput = ControllerDispatcher::me()->render();
			return $this;
		}
		
		public function shutdown()
		{
			$engineEcho = ob_get_contents();
			ob_clean();

			if(strlen($engineEcho)) echo $engineEcho;
			
			Page::me()->getHeader()->output();
			echo $this->renderedOutput;
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
			EngineDispatcher::me()->
				start()->
				render();
			
			exit(1);
		}
	}
?>