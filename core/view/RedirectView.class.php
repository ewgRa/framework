<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RedirectView implements ViewInterface
	{
		private $url = null;

		/**
		 * @return RedirectView
		 */
		public static function create()
		{
			return new self;
		}

		public static function setUrl($url)
		{
			$this->url = $url;
			return $url;
		}

		public function transform(Model $model)
		{

		}

		public function toString()
		{
			return $this->url;
		}
	}
?>