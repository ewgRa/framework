<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DatabaseTaggedQuery extends DatabaseQuery
	{
		private $tags = null;

		/**
		 * @return DatabaseTaggedQuery
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return DatabaseTaggedQuery
		 */
		public function addTags(array $tags)
		{
			$this->tags = array_unique(array_merge($this->tags, $tags));
			return $this;
		}

		/**
		 * @return DatabaseTaggedQuery
		 */
		public function addTag($tag)
		{
			$this->tags[] = $tag;
			return $this;
		}

		/**
		 * @return DatabaseTaggedQuery
		 */
		public function setTags(array $tags)
		{
			$this->tags = $tags;
			return $this;
		}

		public function getTags()
		{
			return $this->tags;
		}
	}
?>