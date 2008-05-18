<?php
	class Variables
	{
		public static function getValueByString($variableName)
		{
			$result = null;
			
			if(defined($variableName))
			{
				$result = constant($variableName);
			}
			else
			{
				$variableMatches = null;
				
				if(
					preg_match(
						'/^(\$?\w+)(?:\[(?:\'|")?(\w+?)(?:\'|")?\])?/',
						$variableName,
						$variableMatches
					)
				)
				{
					$varName = $variableMatches[1];

					if(isset($variableMatches[2]))
					{
						if($varName[0] == '$')
							$varName .= "['" . $variableMatches[2] . "']";
						else 
							$varName = null;
					}
					elseif($varName != $variableName)
					{
						$varName = null;
					}

					if($varName)
					{
						if($varName[0] == '$')
						{
							eval(
								'$result = isset('
								. $varName. ') ? '
								. $varName. ' : null;'
							);	
						}
						else 
						{
							if(defined($varName))
							{
								$result = constant($varName);
							}
						}
					}
				}
			}
			
			return $result;
		}
		
		public static function registerAsConstants(array $variables)
		{
			foreach($variables as $constName => $constValue)
			{
				if(!defined($constName))
				{
					define($constName, $constValue);
				}
			}			
		}
	}
?>