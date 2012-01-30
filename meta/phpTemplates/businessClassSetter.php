<?php
	namespace ewgraFramework;

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

	if ($type == 'boolean') {
		$value = '($'.$name. ' === true)';

		if ($nullable)
			$value = '($'.$name.' === null ? null : '.$value.')';
	}
?>

		/**
		 * @return Auto<?=$generateClassName.PHP_EOL?>
		 */
		public function set<?=$upperName?>(<?=$typeHint?><?=$name?><?=$defaultValue?>)
		{
<?php
	if ($class && $classType == 'Identifier') {
		if ($nullable) {
?>
			$this-><?=$name?>Id =
				$<?=$name?> === null
					? null
					: <?=$value?>->getId();

<?php
		} else {
?>
			$this-><?=$name?>Id = <?=$value?>->getId();
<?php
		}
	}

	if ($identifierId = $property->getAttribute('identifierId')) {
?>
			$this-><?=$identifierId?> = null;
<?php
	}
?>
			$this-><?=$name?> = <?=$value?>;

			return $this;
		}
