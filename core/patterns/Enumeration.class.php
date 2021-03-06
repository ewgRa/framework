<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class Enumeration
	{
		private $id = null;

		protected $names = array();

		public static function create($id)
		{
			$className = get_called_class();

			return new $className($id);
		}

		public static function any()
		{
			$class = get_called_class();
			$reflection = new \ReflectionClass($class);

			$props = $reflection->getDefaultProperties();

			return new $class(key($props['names']));
		}

		public static function createList()
		{
			return self::any()->getList();
		}

		/**
		 * @return Enumeration
		 */
		protected function __construct($id)
		{
			if (!isset($this->names[$id])) {
				throw
					MissingArgumentException::create(
						'known nothing about id='.$id
					);
			}

			$this->id = $id;
		}

		public function getId()
		{
			return $this->id;
		}

		public function getName()
		{
			return $this->names[$this->getId()];
		}

		public function getLowerName()
		{
			return StringUtils::toLower($this->getName());
		}

		public function getNames()
		{
			return $this->names;
		}

		public function getList()
		{
			$result = array();

			foreach ($this->names as $id => $name)
				$result[$id] = $this->create($id);

			return $result;
		}

		public function __toString()
		{
			return (string)$this->getName();
		}
	}
?>