<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class FileBasedCacheTestCase extends BaseCacheTest
	{
		protected function getRealization()
		{
			return
				FileBasedCache::create()->setCacheDir(
					TMP_DIR . DIRECTORY_SEPARATOR . 'cacheData' . __CLASS__
				);
		}
	}
?>