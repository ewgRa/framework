<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	 * // FIXME: tested?
	*/
	final class Model
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
			return isset($this->data[$key]);
		}
		
		public function get($key)
		{
			if(!$this->has($key))
				throw ExceptionsMapper::me()->createException('MissingArgument')->
					setMessage('known nothing about key "' . $key . '"');
				
			return $this->data[$key];
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