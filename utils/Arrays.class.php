<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Arrays
	{
		/**
		 * @link http://ru2.php.net/manual/ru/function.array-merge-recursive.php#42663
		 * @example ../tests/utils/ArraysTest.class.php
		 * @param $arr1, $arr2, ..., $arrN
		 */
		public static function recursiveMerge(array $arr1, array $arr2)
		{
			$arrays = func_get_args();
			
			$result = array_shift($arrays);
			
			foreach($arrays as $array)
				$result = self::merge($result, $array);
			
			return $result;
		}

		private static function merge($one, $two)
		{
			if(!is_array($one) || !is_array($two))
				return $two;
			
			$function = __FUNCTION__;
			
			foreach($two as $key => $value)
				$one[$key] = self::$function(@$one[$key], $value);
			
			return $one;
		}
	}
?>