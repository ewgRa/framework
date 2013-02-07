<?php
	namespace ewgraFramework;

	$meta = $model->get('meta');

	$classNode = $meta->getNode($meta->getNode('className')->nodeValue);

	$extends = $meta->getDocumentElement()->getAttribute('DAExtends');

	echo '<?php'.PHP_EOL;

	$namespace = $meta->getDocumentElement()->getAttribute('namespace');

	if ($namespace) {
?>
	namespace <?=$namespace?>;

<?php
	}
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
		protected $tableAlias = '<?=EnglishStringUtils::separateByUpperKey($classNode->nodeName)?>';

		public function getTag()
		{
			return '<?='\\'.$namespace.'\\'.$classNode->nodeName?>';
		}

		/**
		 * @return array
		 */
		public function getTagList()
		{
<?php
	$tags = array();

	$propertyClasses =
		$meta->getNodeList(
			"properties/*[@class and @classType = 'Identifier']",
			$classNode
		);

	foreach($propertyClasses as $node) {
		$tags[] = (
			strpos($node->getAttribute('class'), '\\') === false
				? '\\'.$namespace.'\\'.$node->getAttribute('class')
				: $node->getAttribute('class')
		);
	}
?>
			return array($this->getTag()<?=$tags ? ", '".join("', '", array_unique($tags))."'" : null?>);
		}

		/**
		 * @return <?=$classNode->nodeName.PHP_EOL?>
		 */
		public function insert(<?=$classNode->nodeName?> $object)
		{
			$result = $this->rawInsert($object);
			$this->dropCache();
			return $result;
		}

		/**
		 * @return <?=$classNode->nodeName.PHP_EOL?>
		 */
		public function rawInsert(<?=$classNode->nodeName?> $object)
		{
			$dialect = $this->db()->getDialect();

			$dbQuery = 'INSERT INTO '.$this->getTable().' ';
			$fields = array();
			$fieldValues = array();
			$values = array();
<?php
	$idProperty =
		$meta->getNode("properties/*[name() = 'id']", $classNode);

	if ($idProperty) {
?>

			if ($object->hasId()) {
				$fields[] = $dialect->escapeField('id');
				$fieldValues[] = '?';
				$values[] = $object->getId();
			}

<?php
	}

	$properties =
		$meta->getNodeList(
			"properties/*[name() != 'id' and not(@classType = 'Identifier')]",
			$classNode
		);

	$queryFields = array();

	foreach ($properties as $property) {
?>
			$fields[] = $dialect->escapeField('<?=$property->getAttribute('downSeparatedName')?>');
			$fieldValues[] = '?';
<?php
		$type = $property->getAttribute('type');

		$class = $property->getAttribute('class');
		$classType = $property->getAttribute('classType');

		$value = '$object->get'.$property->getAttribute('upperName').'()';
		$storeValue = $value;
		$rawValue = $value;

		if ($type == 'array')
			$storeValue = 'serialize('.$value.')';
		else if ($type == 'boolean') {
			$value = '('.$value.' ? 1 : 0)';
			$storeValue = $value;
		} else if ($classType == 'Identifier' || $classType == 'Enumeration') {
			$value = $value.'->getId()';
			$storeValue = $value;
		} else if ($classType == 'Stringable') {
			$value = $value.'->__toString()';
			$storeValue = $value;
		}

		if ($property->getAttribute('nullable')) {
?>

			if (<?=$rawValue?> === null)
				$values[] = null;
			else {
				$values[] = <?=$storeValue?>;
			}

<?php
		} else {
?>
			$values[] = <?=$storeValue?>;
<?php
		}
	}
?>
			$dbQuery .= '('.join(', ', $fields).') VALUES ';
			$dbQuery .= '('.join(', ', $fieldValues).')';

			$dbResult =
				$this->db()->insertQuery(
					\ewgraFramework\DatabaseInsertQuery::create()->
<?php
	if ($idProperty) {
?>
					setPrimaryField('<?=$idProperty->getAttribute('downSeparatedName')?>')->
<?php
	}
?>
					setQuery($dbQuery)->
					setValues($values)
				);

<?php
	if ($idProperty) {
?>
			if (!$object->hasId())
				$object->setId($dbResult->getInsertedId());
<?php
	}
?>

			return $object;
		}

		/**
		 * @return Auto<?=$classNode->nodeName?>DA
		 */
		public function save(<?=$classNode->nodeName?> $object)
		{
			$result = $this->rawSave($object);
			$this->dropCache();
			return $result;
		}

		/**
		 * @return Auto<?=$classNode->nodeName?>DA
		 */
		public function rawSave(<?=$classNode->nodeName?> $object)
		{
			$dialect = $this->db()->getDialect();
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
		$storeValue = $value;
		$rawValue = $value;

		if ($type == 'array')
			$storeValue = 'serialize('.$value.')';
		else if ($type == 'boolean') {
			$value = '('.$value.' ? 1 : 0)';
			$storeValue = $value;
		// FIXME: now in database enumeration store as "enumerationName", change it to "enumerationName_id"?
		} else if ($classType == 'Identifier' || $classType == 'Enumeration') {
			$value = $value.'->getId()';
			$storeValue = $value;
		} else if ($classType == 'Stringable') {
			$value = $value.'->__toString()';
			$storeValue = $value;
		}

		if ($property->getAttribute('nullable')) {
?>

			if (<?=$rawValue?> === null)
				$queryParts[] = $dialect->escapeField('<?=$property->getAttribute('downSeparatedName')?>').' = NULL';
			else {
				$queryParts[] = $dialect->escapeField('<?=$property->getAttribute('downSeparatedName')?>').' = ?';
				$queryParams[] = <?=$storeValue?>;
			}

<?php
		} else {
?>
			$queryParts[] = $dialect->escapeField('<?=$property->getAttribute('downSeparatedName')?>').' = ?';
			$queryParams[] = <?=$storeValue?>;
<?php
		}
	}

	if ($idProperty) {
?>

			$whereParts[] = 'id = ?';
			$queryParams[] = $object->getId();
<?php
	}
?>
			\ewgraFramework\Assert::isNotEmpty($whereParts);

			$dbQuery .= join(', ', $queryParts).' WHERE '.join(' AND ', $whereParts);

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);

			return $object;
		}

		/**
		 * @return Auto<?=$classNode->nodeName?>DA
		 */
		public function delete(<?=$classNode->nodeName?> $object)
		{
			$result = $this->rawDelete($object);
			$this->dropCache();
			return $result;
		}

		/**
		 * @return Auto<?=$classNode->nodeName?>DA
		 */
		public function rawDelete(<?=$classNode->nodeName?> $object)
		{
			$dbQuery =
				'DELETE FROM '.$this->getTable().' WHERE id = '.$object->getId();

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->setQuery($dbQuery)
			);

			$object->setId(null);

			return $this;
		}

		public function getById($id)
		{
			return $this->getCachedByQuery(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery('SELECT * FROM '.$this->getTable().' WHERE id = ?')->
				setValues(array($id))
			);
		}

		public function getByIds(array $ids)
		{
			if (!$ids)
				return array();

			return $this->getListCachedByQuery(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery('SELECT * FROM '.$this->getTable().' WHERE id IN(?)')->
				setValues(array($ids))
			);
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
		else if ($property->getAttribute('type') == 'boolean') {
			if ($property->getAttribute('nullable') == 'true')
				$value = $value.' === null ? null : '.$value.' == true';
			else
				$value = $value.' == true';
		}
		else if ($property->getAttribute('classType') == 'Enumeration') {
			$createValue = $property->getAttribute('class').'::create('.$value. ')';

			if ($property->getAttribute('nullable') == 'true')
				$value = $value.' === null ? null : '.$createValue;
			else
				$value = $createValue;

		} else if ($property->getAttribute('classType') == 'Stringable') {
			$createValue =
				$property->getAttribute('class')
				.'::createFromString('.$value. ')';

			if ($property->getAttribute('nullable') == 'true')
				$value = $value.' === null ? null : '.$createValue;
			else
				$value = $createValue;
		}

		$methods[] = 'set'.$property->getAttribute('upperName').'('.$value.')';
	}
?>
				<?=join("->".PHP_EOL.'				', $methods).';'.PHP_EOL?>
		}
	}
<?php
	echo '?>';
?>