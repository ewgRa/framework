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
			$url = 'http://localhost.ru/path?query=queryString';
			
			$httpUrl = HttpUrl::createFromString($url);
			
			$this->assertSame('http', $httpUrl->getScheme());
			$this->assertSame('localhost.ru', $httpUrl->getHost());
			$this->assertSame('/path', $httpUrl->getPath());
			$this->assertSame('query=queryString', $httpUrl->getQuery());
			$this->assertSame((string)$httpUrl, $url);
		}
	}
?>