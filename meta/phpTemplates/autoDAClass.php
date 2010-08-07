<?php
	$meta = $model->get('meta');
	
	$classNode = $meta->getNode($meta->getNode('className')->nodeValue);
	
	$extends = $meta->getDocumentElement()->getAttribute('DAExtends');

	echo '<?php'.PHP_EOL;
?>
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
<?php
	PhpView::includeFile(
		dirname(__FILE__).'/phpDocClass.php',
		$model
	);
?>
	 */
	abstract class Auto<?=$classNode->nodeName?>DA extends <?=$extends.PHP_EOL?>
	{
		protected $tableAlias = '<?=$classNode->nodeName?>';
		
		/**
		 * @return <?=$classNode->nodeName.PHP_EOL?>
		 */
		public function insert(<?=$classNode->nodeName?> $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
<?php
	$idProperty =
		$meta->getNode("properties/*[name() = 'id']", $classNode);

	$properties =
		$meta->getNodeList(
			"properties/*[name() != 'id' and not(@classType = 'Identifier')]",
			$classNode
		);
		
	foreach ($properties as $property) {
		$type = $property->getAttribute('type');
		
		$class = $property->getAttribute('class');
		$classType = $property->getAttribute('classType');
				
		$value = '$object->get'.$property->getAttribute('upperName').'()';
		
		if ($type == 'array')
			$value = 'serialize('.$value.')';
		else if ($classType == 'Identifier' || $classType == 'Enumeration')
			$value = $value.'->getId()';
		else if ($classType == 'Stringable')
			$value = $value.'->__toString()';
?>
			
			if (!is_null($object->get<?=$property->getAttribute('upperName')?>())) {
				$queryParts[] = '<?=$property->getAttribute('downSeparatedName')?> = ?';
				$queryParams[] = <?=$value?>;
			}
<?php
	}
?>
			
			$dbQuery .= join(', ', $queryParts);
			
			$this->db()->query(
				DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);
			
<?php
	if ($idProperty) {
?>
			$object->setId($this->db()->getInsertedId());
<?php
	}
?>
			
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return Auto<?=$classNode->nodeName?>DA
		 */
		public function save(<?=$classNode->nodeName?> $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			
<?php
	foreach ($properties as $property) {
		$type = $property->getAttribute('type');
		
		$class = $property->getAttribute('class');
		$classType = $property->getAttribute('classType');
				
		$value = '$object->get'.$property->getAttribute('upperName').'()';
		
		if ($type == 'array')
			$value = 'serialize('.$value.')';
		else if ($classType == 'Identifier' || $classType == 'Enumeration')
			$value = $value.'->getId()';
		else if ($classType == 'Stringable')
			$value = $value.'->__toString()';
?>
			$queryParts[] = '<?=$property->getAttribute('downSeparatedName')?> = ?';
			$queryParams[] = <?=$value?>;
<?php
	}

	if ($idProperty) {
?>
			
			$whereParts[] = 'id = ?';
			$queryParams[] = $object->getId();
<?php
	}
?>
			Assert::isNotEmpty($whereParts);
			
			$dbQuery .= join(', ', $queryParts).' WHERE '.join(' AND ', $whereParts);

			$this->db()->query(
				DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);
			 
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return <?=$classNode->nodeName.PHP_EOL?>
		 */
		public function build(array $array)
		{
			return
				<?=$classNode->nodeName?>::create()->
<?php
	$properties =
		$meta->getNodeList(
			"properties/*[not(@classType = 'Identifier')]",
			$classNode
		);
	
	$methods = array();
	
	foreach ($properties as $property) {
		$value = '$array[\''.$property->getAttribute('downSeparatedName').'\']';
		
		if ($property->getAttribute('type') == 'array')
			$value = $value. ' ? unserialize('.$value.') : null';
		else if ($property->getAttribute('type') == 'boolean')
			$value = $value. ' == true';
		else if ($property->getAttribute('classType') == 'Enumeration')
			$value = $property->getAttribute('class').'::create('.$value. ')';
		else if ($property->getAttribute('classType') == 'Stringable') {
			$value = 
				$property->getAttribute('class')
				.'::createFromString('.$value. ')';
		}
		
		$methods[] = 'set'.$property->getAttribute('upperName').'('.$value.')';
	}
?>
				<?=join("->".PHP_EOL.'				', $methods).';'.PHP_EOL?>
		}
<?php
		$relationNodes = 
			$meta->getNodeList("*[properties/*[@class='".$classNode->nodeName."']]");
		
		if ($relationNodes->length) {
?>

		public function dropCache()
		{
<?php 
			foreach($relationNodes as $node) {
?>
			<?=$node->nodeName?>::da()->dropCache();
<?php 
			}
?>
			return parent::dropCache();
		}
<?php			
		}
?>
	}
<?php
	echo '?>';
?>