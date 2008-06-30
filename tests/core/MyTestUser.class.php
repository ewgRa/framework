<?php
	/* $Id$ */

	class MyTestUser extends User
	{
		public static function ftSetId($id)
		{
			return self::me()->setId($id);
		}

		public static function ftLoadRights()
		{
			return self::me()->loadRights();
		}
	}
?>