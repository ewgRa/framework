<?php
	namespace ewgraFramework;

	$meta = $model->get('meta');
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
	 * Do not edit this file!
	*/

	\ewgraFramework\ClassesAutoloader::me()->addSearchDirectory(dirname(__FILE__)<?=($namespace ? ", __NAMESPACE__" : null)?>);
<?php
	echo '?>';
?>