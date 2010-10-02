<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseDatabaseResult implements DatabaseResultInterface
	{
		private $resource	= null;
		
		public function setResource($resource)
		{
			$this->resource = $resource;
			return $this;
		}

		public function getResource()
		{
			return $this->resource;
		}
		
		public function fetchList()
		{
			$result = array();
			
			if (
				$this->getResource()
				&& $this->recordCount($this->getResource())
			) {
				$this->dataSeek(1);
				
				while ($row = $this->fetchRow())
					$result[] = $row;
			}
			
			return $result;
		}

		/**
		 * @throws MissingArgumentException
		 */
		public function fetchFieldList($field, $keyField = null)
		{
			$result = array();
			
			if (
				$this->getResource()
				&& $this->recordCount($this->getResource())
			) {
				$this->dataSeek(1);
				
				if ($keyField) {
					$row = $this->fetchRow();
				
					if (!isset($row[$keyField])) {
						throw MissingArgumentException::create(
							"result row doesn't have keyfield '".$keyField."'"
						);
					}

					$this->dataSeek(1);
				}
				
				while ($row = $this->fetchRow()) {
					if ($keyField)
						$result[$row[$keyField]] = $row[$field];
					else
						$result[] = $row[$field];
				}
			}
			
			return $result;
		}
	}
?>