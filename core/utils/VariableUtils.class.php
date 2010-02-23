<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class VariableUtils
	{
		public static function getValueByString($variableName)
		{
			$result = null;
			
			if (defined($variableName))
				$result = constant($variableName);
			else {
				$variableMatches = null;
				
				if (
					preg_match(
						'/^(\$\w+)(?:\[(?:\'|")?(\w+?)(?:\'|")?\])?/',
						$variableName,
						$variableMatches
					)
				) {
					$varName = $variableMatches[1];

					if (isset($variableMatches[2]))
						$varName .= "['" . $variableMatches[2] . "']";
					elseif ($varName != $variableName)
						$varName = null;

					if ($varName) {
						eval(
							'$result = isset('
							. $varName. ') ? '
							. $varName. ' : null;'
						);
					}
				}
			}
			
			return $result;
		}
		
		public static function registerAsConstants(array $variables)
		{
			foreach ($variables as $constName => $constValue) {
				if (!defined($constName))
					define($constName, $constValue);
			}
			
			return true;
		}
	}
?>