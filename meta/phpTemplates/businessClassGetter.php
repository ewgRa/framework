<?php
	namespace ewgraFramework;
?>

<?php
	$name = $property->nodeName;
	$upperName = $property->getAttribute('upperName');
	$type = $property->getAttribute('type');
	$class = $property->getAttribute('class');
	$classType = $property->getAttribute('classType');

	$returnValue = null;

	if ($class)
		$returnValue = $class;
	else if($type)
		$returnValue = $type;

	if ($returnValue) {
?>
		/**
		 * @return <?=$returnValue.PHP_EOL?>
		 */
<?php
	}
?>
		public function get<?=$upperName?>()
		{
<?php
	if ($class) {
		if ($classType == 'Identifier') {
?>
			if (!$this-><?=$name?> && $this->get<?=$upperName?>Id())
				$this-><?=$name?> = <?=$class?>::da()->getById($this->get<?=$upperName?>Id());

			return $this-><?=$name?>;
<?php
		} else if(in_array($classType, array('Enumeration', 'Stringable'))) {
?>
			return $this-><?=$name?>;
<?php
		} else
			\ewgraFramework\Assert::isUnreachable();
	} else {
		if ($name == 'id') {
?>
			\ewgraFramework\Assert::isNotNull($this-><?=$name?>);
<?php
		}
?>
			return $this-><?=$name?>;
<?php
	}
?>
		}
<?php
	if ($type == 'boolean') {
?>

		public function is<?=$upperName?>()
		{
			return ($this->get<?=$upperName?>() === true);
		}
<?php
	}

	if ($name == 'id') {
?>

		public function has<?=$upperName?>()
		{
			return ($this-><?=$name?> !== null);
		}
<?php
	}
?>