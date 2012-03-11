<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Pager
	{
		private $total = null;
		private $offset = null;
		private $limit = null;

		private $offsetKey 	= 'offset';
		private $pageKey 	= 'page';

		private $radius = null;

		/**
		 * @return Pager
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return Pager
		 */
		public function setTotal($total)
		{
			$this->total = $total;
			return $this;
		}

		public function getTotal()
		{
			return $this->total;
		}

		/**
		 * @return Pager
		 */
		public function setOffset($offset)
		{
			$this->offset = $offset;
			return $this;
		}

		public function getOffset()
		{
			return $this->offset;
		}

		/**
		 * @return Pager
		 */
		public function setRadius($radius)
		{
			$this->radius = $radius;
			return $this;
		}

		public function getRadius()
		{
			return $this->radius;
		}

		/**
		 * @return Pager
		 */
		public function setLimit($limit)
		{
			$this->limit = $limit;
			return $this;
		}

		public function getLimit()
		{
			return $this->limit;
		}

		/**
		 * @return Pager
		 */
		public function setOffsetKey($key)
		{
			$this->offsetKey = $key;
			return $this;
		}

		public function getOffsetKey()
		{
			return $this->offsetKey;
		}

		public function getStartPage()
		{
			$result = $this->getPage()-$this->getRadius();
			$endPage = $this->getPage()+$this->getRadius();

			if ($endPage > $this->getPagesCount())
				$result -= ($endPage - $this->getPagesCount());

			if ($result < 1)
				$result = 1;

			return $result;
		}

		public function getEndPage()
		{
			$result = $this->getPage()+$this->getRadius();
			$startPage = $this->getPage()-$this->getRadius();

			if ($startPage < 1)
				$result += abs($startPage)+1;

			if ($result > $this->getPagesCount())
				$result = $this->getPagesCount();

			return $result;
		}

		public function getPrevPage()
		{
			$result =
				$this->getPage() > 1
					? $this->getPage()-1
					: null;

			if ($result && $result > $this->getPagesCount())
				$result = $this->getPagesCount();

			if (!$result)
				$result = null;

			return $result;
		}

		public function getNextPage()
		{
			return
				$this->getPage() < $this->getPagesCount()
					? $this->getPage()+1
					: null;
		}

		public function getPagesCount()
		{
			\ewgraFramework\Assert::isNotNull($this->getLimit());
			return ceil($this->getTotal() / $this->getLimit());
		}

		/**
		 * @return Pager
		 */
		public function setPage($page)
		{
			\ewgraFramework\Assert::isNotNull($this->getLimit());
			$this->setOffset(($page-1)*$this->getLimit());

			return $this;
		}

		public function getPage()
		{
			\ewgraFramework\Assert::isNotNull($this->getLimit());
			return floor($this->getOffset()/$this->getLimit())+1;
		}

		/**
		 * @return Pager
		 */
		public function setPageKey($key)
		{
			$this->pageKey = $key;
			return $this;
		}

		public function getPageKey()
		{
			return $this->pageKey;
		}

		public function fillFromRequest(HttpRequest $request)
		{
			$form =
				\ewgraFramework\Form::create()->
				addPrimitive(
					\ewgraFramework\PrimitiveInteger::create($this->getOffsetKey())->
					setDefaultValue(0)
				)->
				addPrimitive(
					\ewgraFramework\PrimitiveInteger::create($this->getPageKey())->
					setDefaultValue(1)
				);

			$form->
				import($request->getGet())->
				importMore($request->getPost());

			if ($form->getRawValue($this->getPageKey()))
				$this->setPage($form->getSafeValue($this->getPageKey()));
			else
				$this->setOffset($form->getSafeValue($this->getOffsetKey()));

			return $this;
		}
	}
?>