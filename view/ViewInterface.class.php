<?php
	/* $Id$ */

	/**
	 * @license http://opensource.org/licenses/gpl-3.0.html GPLv3
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * @copyright Copyright (c) 2008, Evgeniy Sokolov
	*/
	interface ViewInterface
	{
		public static function create();

		public function loadLayout($file);
		
		public function transform(Model $model);
		
		public function toString();
	}
?>