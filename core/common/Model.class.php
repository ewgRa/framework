<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class Model
	{
		private $data = array();

		/**
		 * @return Model
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return Model
		 */
		public function setData(array $data)
		{
			$this->data = $data;
			return $this;
		}

		public function getData()
		{
			return $this->data;
		}

		/**
		 * @return Model
		 */
		public function set($key, $value)
		{
			$this->data[$key] = $value;
			return $this;
		}

		public function has($key)
		{
			return array_key_exists($key, $this->data);
		}

		public function get($key)
		{
			if (!$this->has($key)) {
				throw MissingArgumentException::create(
					'known nothing about key "'.$key.'"'
				);
			}

			return $this->data[$key];
		}

		public function drop($key)
		{
			if ($this->has($key))
				unset($this->data[$key]);

			return $this;
		}

		/**
		 * @return Model
		 */
		public function append($data)
		{
			$this->data[] = $data;
			return $this;
		}

		/**
		 * @return Model
		 */
		public function merge(array $array)
		{
			$this->data = array_merge($this->data, $array);
			return $this;
		}

		/**
		 * @return Model
		 */
		public function mergeModel(Model $model)
		{
			$this->merge($model->getData());
			return $this;
		}
	}
?>