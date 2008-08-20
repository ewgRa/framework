<?php
	/* $Id$ */
	
	// FIXME: tested?
	class EngineDispatcher extends Singleton
	{
		private $fired = false;
		
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

		public function setFired()
		{
			$this->fired = true;
			return $this;
		}

		public function isFired()
		{
			return $this->fired;
		}
		
		public function start()
		{
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
			
			Localizer::me()->defineLanguage();
			
			// TODO: check cache data for path. if no cache, load page, and then
			// 	 check preg pages
			
			$cacheTicket = Cache::me()->createTicket()->
				setPrefix('pagepathmapper')->
				setActualTime(time() + PagePathMapper::CACHE_LIFE_TIME)->
				restoreData();
			
			if($cacheTicket->isExpired())
			{
				PagePathMapper::me()->loadMap();
				$cacheTicket->setData(PagePathMapper::me())->storeData();
			}
			else
				Singleton::setInstance('PagePathMapper', $cacheTicket->getData());
			
			$pageId = PagePathMapper::me()->getPageId(
				UrlHelper::me()->getEnginePagePath()
			);

			$cacheTicket = Cache::me()->createTicket()->
				setPrefix('page')->
				setKey(
					$pageId ? $pageId : UrlHelper::me()->getEnginePagePath()
				)->
				setActualTime(time() + BasePage::CACHE_LIFE_TIME)->
				restoreData();
			
			if($cacheTicket->isExpired())
			{
				Page::create(UrlHelper::me()->getEnginePagePath(), $pageId);
				$cacheTicket->setData(Page::me())->storeData();
			}
			else
				Singleton::setInstance('Page', $cacheTicket->getData());

			Page::me()->
				setRequestPath(UrlHelper::me()->getEnginePagePath())->
				processPath();
			
			if(Page::me()->getViewType() == View::AJAX)
				JsHttpRequest::initialize(Config::me()->getOption('charset'));
			
			Page::me()->checkAccessPage(User::me()->getRights());
			
			ControllerDispatcher::me()->loadControllers(Page::me()->getId());
			
			var_dump(ControllerDispatcher::me()->render());

			die;
			
			return $this;
		}
		
		private function shutdown()
		{
			
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
	}
?>