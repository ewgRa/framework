<?php
	namespace ewgraFramework;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ArrayUtils
	{
		public static function getObjectIds(array $objects)
		{
			$result = array();

			foreach ($objects as $object)
				$result[] = $object->getId();

			return $result;
		}

		public static function convertObjectList(array $objects)
		{
			$result = array();

			foreach ($objects as $object)
				$result[$object->getId()] = $object;

			return $result;
		}

		public static function objectsToArray(array $mixed)
		{
			foreach ($mixed as &$value)
				if (is_array($value))
					$value = self::objectsToArray($value);
				else if (is_object($value) && $value instanceof ArrayableInterface)
					$value = $value->toArray();
				else if (is_object($value))
					$value = (array)$value;

			return $mixed;
		}

		/**
		 * @link http://ru2.php.net/manual/ru/function.array-merge-recursive.php#42663
		 * @param $arr1, $arr2, ..., $arrN
		 */
		public static function recursiveMerge(array $arr1, array $arr2)
		{
			$arrays = func_get_args();

			$result = array_shift($arrays);

			foreach ($arrays as $array)
				$result = self::merge($result, $array);

			return $result;
		}

		private static function merge($one, $two)
		{
			if (!is_array($one) || !is_array($two))
				return $two;

			$function = __FUNCTION__;

			foreach ($two as $key => $value)
				$one[$key] = self::$function(@$one[$key], $value);

			return $one;
		}
	}
?>