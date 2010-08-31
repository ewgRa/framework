<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveUploadFile extends BasePrimitive
	{
		/**
		 * @var File
		 */
		private $file = null;
		
		/**
		 * @return PrimitiveUploadFile
		 */
		public static function create($name)
		{
			return new self($name);
		}

		/**
		 * @return File
		 */
		public function getFile()
		{
			return $this->file;
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function clean()
		{
			$this->file = null;
			
			return parent::clean();
		}
		
		/**
		 * @return BasePrimitive
		 */
		public function importValue($value)
		{
			$result = $this;
			
			if ($value['error'] && $value['tmp_name'])
				$this->addError(PrimitiveErrors::UPLOAD_ERROR);
			else if(!$value['tmp_name'] && $this->isRequired())
				$this->markMissing();
			else {
				$result = parent::importValue($value['name']);
				$this->file = File::create()->setPath($value['tmp_name']);
			}
			
			return $result;
		}
	}
?>