<?php
	$name = $property->nodeName;
	$upperName = $property->getAttribute('upperName');
	$type = $property->getAttribute('type');
	$class = $property->getAttribute('class');
	$classType = $property->getAttribute('classType');
	
	$nullable = $property->getAttribute('nullable');
	
	$typeHint = '$';
	
	if ($class)
		$typeHint = $class.' '.$typeHint;
	else if($type == 'array')
		$typeHint = $type.' '.$typeHint;
	
	$defaultValue = null;
	
	if ($type == 'boolean')
		$defaultValue = ' = true';
	else if ($nullable && $type != 'boolean')
		$defaultValue = ' = null';
		
	$value = '$'.$name;
	
	if ($type == 'boolean')
		$value = '($'.$name. ' === true)';
?>
		
		/**
		 * @return Auto<?=$generateClassName.PHP_EOL?>
		 */
		public function set<?=$upperName?>(<?=$typeHint?><?=$name?><?=$defaultValue?>)
		{
<?php
	if ($class && $classType == 'Identifier') {
?>
			$this-><?=$name?>Id = <?=$value?>->getId();
<?php
	} else {
?>
			$this-><?=$name?> = <?=$value?>;
<?php
	}
?>
			return $this;
		}
