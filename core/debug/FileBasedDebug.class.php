<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * TODO: realize me
	*/
	final class FileBasedDebug extends BaseDebug
	{
		public static function create()
		{
			return new self;
		}
		
		public function store()
		{
			throw UnimplementedCodeException::create();
		}
	}
?>