<?php
	/* $Id$ */

	interface ViewInterface
	{
		public static function create();

		public function loadLayout($file);
		
		public function transform(array $model);
		
		public function toString();
	}
?>