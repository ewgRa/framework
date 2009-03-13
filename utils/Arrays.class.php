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
		 */
		public static function recursiveMerge($arr1, $arr2)
		{
			if(!is_array($arr1) || !is_array($arr2))
				return $arr2;
			
			foreach($arr2 as $key => $value)
				$arr1[$key] = self::recursiveMerge(@$arr1[$key], $value);
			
			return $arr1;
		}
		
		/**
		 * @example ../tests/utils/ArraysTest.class.php
		 */
		public static function recursiveMergeByArrayKeys($array, array $keys)
		{
			$result = array();
			
			foreach($keys as $key)
			{
				if(isset($array[$key]))
					$result = self::recursiveMerge($result, $array[$key]);
			}
			
			return $result;
		}
	}
?>