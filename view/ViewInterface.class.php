<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface ViewInterface
	{
		public static function create();

		public function loadLayout($filePath, $fileId = null);
		
		public function transform(Model $model);
		
		public function toString();
	}
?>