<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class HttpUrlTestCase extends FrameworkTestCase
	{
		public function testParse()
		{
			$httpUrl = HttpUrl::createFromString(
				'http://localhost.ru/path?query=queryString'
			);
			
			$this->assertSame('http', $httpUrl->getScheme());
			$this->assertSame('localhost.ru', $httpUrl->getHost());
			$this->assertSame('/path', $httpUrl->getPath());
			$this->assertSame('query=queryString', $httpUrl->getQuery());
		}
	}
?>