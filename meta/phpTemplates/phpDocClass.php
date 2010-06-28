<?php
	if ($license = $meta->getDocumentElement()->getAttribute('license')) {
?>
	 * @license <?=$license.PHP_EOL?>
<?php
	}

	if ($author = $meta->getDocumentElement()->getAttribute('author')) {
?>
	 * @author <?=$author.PHP_EOL?>
<?php
	}
?>