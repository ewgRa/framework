<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveUploadFile extends BasePrimitive
	{
		/**
		 * @return PrimitiveString
		 */
		public static function create($name)
		{
			return new self($name);
		}

		/**
		 * @return BasePrimitive
		 */
		public function importValue($value)
		{
			$result = $this;
			
			if ($value['error'])
				$this->addError(PrimitiveErrors::UPLOAD_ERROR);
			else
				$result = parent::importValue($value['name']);
			
			return $result;
		}
	}
?>