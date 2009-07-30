<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class VariableUtils
	{
		/**
		 * @example ../tests/utils/VariableUtilsTest.class.php
		 */
		public static function getValueByString($variableName)
		{
			$result = null;
			
			if(defined($variableName))
				$result = constant($variableName);
			else
			{
				$variableMatches = null;
				
				if(
					preg_match(
						'/^(\$?\w+)(?:\[(?:\'|")?(\w+?)(?:\'|")?\])?/',
						$variableName,
						$variableMatches
					)
				) {
					$varName = $variableMatches[1];

					if(isset($variableMatches[2]))
					{
						if(substr($varName, 0, 1) == '$')
							$varName .= "['" . $variableMatches[2] . "']";
						else
							$varName = null;
					}
					elseif($varName != $variableName)
						$varName = null;

					if($varName)
					{
						if(substr($varName, 0, 1) == '$')
						{
							eval(
								'$result = isset('
								. $varName. ') ? '
								. $varName. ' : null;'
							);
						}
						elseif(defined($varName))
							$result = constant($varName);
					}
				}
			}
			
			return $result;
		}
		
		/**
		 * @example utils/VariableUtilsTest.class.php
		 */
		public static function registerAsConstants(array $variables)
		{
			foreach($variables as $constName => $constValue)
			{
				if(!defined($constName))
					define($constName, $constValue);
			}
			
			return true;
		}
	}
?>