<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class RedirectView implements ViewInterface
	{
		/**
		 * @var HttpUrl
		 */
		private $url = null;

		/**
		 * @return RedirectView
		 */
		public static function create()
		{
			return new self;
		}

		public function setUrl(HttpUrl $url)
		{
			$this->url = $url;
			return $this;
		}

		public function transform(Model $model)
		{
			throw UnimplementedCodeException::create();
		}

		public function toString()
		{
			return $this->url->__toString();
		}
	}
?>