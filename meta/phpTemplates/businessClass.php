<?php
	$meta = $model->get('meta');
	
	$classNode = $meta->getNode($meta->getNode('className')->nodeValue);
	
	echo '<?php'.PHP_EOL;
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
	final class <?=$classNode->nodeName?> extends Auto<?=$classNode->nodeName.PHP_EOL?>
	{
		/**
		 * @return <?=$classNode->nodeName.PHP_EOL?>
		 */
		public static function create()
		{
			return new self;
		}
	}
<?php
	echo '?>';
?>