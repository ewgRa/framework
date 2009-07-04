<?php
	/* $Id$ */

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
		
		public function fetchList($field = null, $keyField = null)
		{
			$result = array();
			
			if($this->getResource() && $this->recordCount($this->getResource()))
			{
				$this->dataSeek(1);
				
				while($row = $this->fetchArray())
				{
					if($keyField && isset($row[$keyField]))
						$result[$row[$keyField]] = is_null($field) ? $row : $row[$field];
					else
						$result[] = is_null($field) ? $row : $row[$field];
				}
			}
			
			return $result;
		}
	}
?>