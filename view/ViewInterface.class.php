<?php
	/* $Id$ */

	interface ViewInterface
	{
		public static function create();

		public function loadLayout($file);
		
		public function transform($model);
		
		public function toString();
	}
?>