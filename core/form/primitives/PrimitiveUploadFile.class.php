<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PrimitiveUploadFile extends PrimitiveArray
	{
		const UPLOAD_ERROR = 'uploadError';
		const EXTENSION_ERROR = 'extensionError';

		private $originalFileName = null;

		private $allowedExtensions = null;

		/**
		 * @return PrimitiveUploadFile
		 */
		public static function create($name)
		{
			return new self($name);
		}

		public function getOriginalFileName()
		{
			return $this->originalFileName;
		}

		/*
		 * @return PrimitiveUploadFile
		 */
		public function setAllowedExtensions(array $allowedExtensions)
		{
			$this->allowedExtensions = $allowedExtensions;
			return $this;
		}

		/**
		 * @return BasePrimitive
		 */
		public function import($scope)
		{
			$result = parent::import($scope);

			if (!$this->hasErrors() && $this->getValue() !== null) {
				$value = $this->getValue();

				$extension =
					StringUtils::toLower(
						pathinfo($value['name'], PATHINFO_EXTENSION)
					);

				if (!empty($value['error']) && !empty($value['tmp_name'])) {
					$this->addError(self::UPLOAD_ERROR);
				}

				if (
					$this->allowedExtensions
					&& !in_array($extension, $this->allowedExtensions)
				) {
					$this->addError(self::EXTENSION_ERROR);
				}

				if ($this->hasErrors())
					$this->dropValue();
				else {
					$this->setValue(File::create()->setPath($value['tmp_name']));
					$this->originalFileName = $value['name'];
				}
			}

			return $result;
		}

		public function clean()
		{
			$this->originalFileName = null;
			return parent::clean();
		}

		public function isEmpty($value)
		{
			return
				parent::isEmpty($value)
				|| empty($value['tmp_name']);
		}
	}
?>