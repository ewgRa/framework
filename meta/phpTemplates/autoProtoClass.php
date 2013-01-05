<?php
	namespace ewgraFramework;

	$meta = $model->get('meta');

	$classNode = $meta->getNode($meta->getNode('className')->nodeValue);

	$extends = $meta->getDocumentElement()->getAttribute('ProtoExtends');

	if (!$extends)
		$extends = '\ewgraFramework\ProtoObject';

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

	$properties = $meta->getNodeList("properties/*", $classNode);

	$dbFields = array();

	foreach ($properties as $property) {
		if ($property->getAttribute('classType') == 'Identifier')
			continue;

		$dbFields[] =
			'"'.(
				$property->getAttribute('identifierId')
					? $property->getAttribute('identifierId')
					: $property->nodeName
			).'" => '
			.' "'.$property->getAttribute('downSeparatedName').'"';
	}
?>
	 */
	abstract class Auto<?=$classNode->nodeName?>Proto extends <?=$extends.PHP_EOL?>
	{
<?php
	$needEndLine = false;

	foreach ($properties as $property) {
		if($property->getAttribute('maxLength')) {
			$needEndLine = true;
?>
		const MAX_<?=StringUtils::toUpper($property->getAttribute('downSeparatedName'))?>_LENGTH = <?=$property->getAttribute('maxLength')?>;
<?php
		}
	}

	if ($needEndLine)
		echo PHP_EOL;
?>
		protected $dbFields = array(
			<?=join(','.PHP_EOL.'			',$dbFields).PHP_EOL?>
		);
	}
<?php
	echo '?>';
?>