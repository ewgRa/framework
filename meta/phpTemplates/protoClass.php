<?php
	namespace ewgraFramework;

	$meta = $model->get('meta');

	$classNode = $meta->getNode($meta->getNode('className')->nodeValue);

	echo '<?php'.PHP_EOL;

	$namespace = $meta->getDocumentElement()->getAttribute('namespace');

	if ($namespace) {
?>
	namespace <?=$namespace?>;

<?php
	}
?>
	/**
	 * Generated by meta builder, you can edit this class
<?php
	PhpView::includeFile(
		dirname(__FILE__).'/phpDocClass.php',
		$model
	);
?>
	 */
	final class <?=$classNode->nodeName?>Proto extends Auto<?=$classNode->nodeName?>Proto
	{
		/**
		 * @return <?=$classNode->nodeName?>Proto
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}
	}
<?php
	echo '?>';
?>