<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Page
	{
		private $id				= null;
		private $layoutFileId	= null;
		private $preg			= null;
		private $rights			= null;
		private $path			= null;
		
		private $baseUrl		= null;
		
		private $header			= null;
		
		/**
		 * @var PageDA
		 */
		private $da				= null;
		
		/**
		 * @return Page
		 */
		public static function create()
		{
			return new self;
		}
		
		public function __construct()
		{
			$this->header = PageHeader::create();
		}

		/**
		 * @return PageDA
		 */
		public function da()
		{
			if(!$this->da)
				$this->da = PageDA::create();
				
			return $this->da;
		}
		
		/**
		 * @return Page
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}

		public function getId()
		{
			return $this->id;
		}

		/**
		 * @return Page
		 */
		public function setLayoutFileId($fileId)
		{
			$this->layoutFileId = $fileId;
			return $this;
		}

		public function getLayoutFileId()
		{
			return $this->layoutFileId;
		}

		/**
		 * @return Page
		 */
		public function setBaseUrl(HttpUrl $url)
		{
			$this->baseUrl = $url;
			return $this;
		}

		public function getBaseUrl()
		{
			return $this->baseUrl;
		}
		
		/**
		 * @return Page
		 */
		public function setPath($path)
		{
			$this->path = $path;
			return $this;
		}

		public function getPath()
		{
			return $this->path;
		}
		
		/**
		 * @return Page
		 */
		public function setPreg()
		{
			$this->preg = true;
			return $this;
		}

		public function isPreg()
		{
			return $this->preg == true;
		}
		
		/**
		 * @return Page
		 */
		public function setRights(array $rights)
		{
			$this->rights = $rights;
			return $this;
		}

		public function getRights()
		{
			return $this->rights;
		}

		/**
		 * @return Page
		 */
		public function loadRights()
		{
			$this->rights = array();

			foreach($this->da()->getRights($this->getId()) as $right)
			{
				$this->rights[$right['right_id']] = array(
					'redirectPage' => $right['redirect_page'],
					'rightAlias' => $right['right_alias']
				);
			}

			return $this;
		}
		
		/**
		 * @return Page
		 */
		public function checkAccessPage(User $user)
		{
			if($this->getRights())
			{
				$intersectRights = array_intersect(
					array_keys($this->getRights()), array_keys($user->getRights())
				);

				if(!count($intersectRights))
				{
					$noRights = array_diff(
						array_keys($this->getRights()), $intersectRights
					);
					
					throw
						ExceptionsMapper::me()->createException('Page')->
							setCode(PageException::NO_RIGHTS_TO_ACCESS)->
							setNoRights($noRights)->
							setPageRights($this->getRights());
					}
			}

			return $this;
		}

		/**
		 * @return PageHeader
		 */
		public function getHeader()
		{
			return $this->header;
		}
		
		/**
		 * @return Page
		 */
		public function load($pageId)
		{
			$page = $this->da()->getPage($pageId);
			
			if($page['preg'])
				$this->setPreg();
						
			$this->
				setId($page['id'])->
				setLayoutFileId($page['layout_file_id'])->
				setPath(
					Config::me()->replaceVariables($page['path'])
				)->
				loadRights();

			return $this;
		}
	}
?>