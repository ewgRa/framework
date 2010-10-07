<?php
	namespace ewgraFramework;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveUploadFile extends BasePrimitive
	{
		const UPLOAD_ERROR = 'uploadError';
		
		/**
		 * @return PrimitiveUploadFile
		 */
		public static function create($name)
		{
			return new self($name);
		}

		/**
		 * @return BasePrimitive
		 */
		public function import($scope)
		{
			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue()) {
				Assert::isArray($this->getValue());
			
				$value = $this->getValue();
			
				if (!empty($value['error']) && !empty($value['tmp_name'])) {
					$this->addError(self::UPLOAD_ERROR);
					$this->dropValue();
				} else
					$this->setValue(File::create()->setPath($value['tmp_name']));
			}
			
			return $result;
		}
		
		public function isEmpty($value)
		{
			return 
				parent::isEmpty($value)
				|| empty($value['tmp_name']); 
		}
	}
?>