<?php
	$meta = $model->get('meta');
	echo '<?php'.PHP_EOL;
?>
	ClassesAutoloader::me()->addSearchDirectories(array(dirname(__FILE__)));
	
<?php
	foreach (
		$meta->getNodeList(
			'*/properties/*[@class=name(/meta/*[@generate="false" and @type="Identifier"])]'
		) as $node
	) {
		$parent = $node->parentNode->parentNode;		
?>
	<?=$node->getAttribute('class')?>::da()->addLinkedCacher(<?=$parent->nodeName?>::da());
<?php
	}

	echo '?>';
?>