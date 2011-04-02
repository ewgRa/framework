<?php
	namespace ewgraFramework\tests;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class HttpRequestTestCase extends FrameworkTestCase
	{
		public function testCommon()
		{
			$_SERVER['HTTP_REFERER'] = 'referer';

			$data = array(
				'Get' => array('a' => 'b'),
				'Post' => array('c' => 'd'),
				'Attached' => array('e' => 'f'),
				'Cookie' => array('g' => 'h'),
				'Files' => array('j' => 'k'),
				'Server' => $_SERVER
			);

			$request = \ewgraFramework\HttpRequest::create();
			$this->assertFalse($request->hasHttpReferer());

			$url = \ewgraFramework\HttpUrl::create()->setPath('/aaa');
			$request->setUrl($url);
			$this->assertSame($url, $request->getUrl());

			foreach ($data as $key => $value) {
				$this->assertFalse($request->{'has'.$key}());
				$request->{'set'.$key}($value);
				$this->assertTrue($request->{'has'.$key}());
				$this->assertSame($value, $request->{'get'.$key}());

				$this->assertFalse($request->{'has'.$key.'Var'}('var'));

				try {
					$request->{'get'.$key.'Var'}('var');
					$this->fail();
				} catch (\ewgraFramework\MissingArgumentException $e) {
					# all good
				}

				$request->{'set'.$key.'Var'}('var', 'value');
				$this->assertSame('value', $request->{'get'.$key.'Var'}('var'));
				$this->assertTrue($request->{'has'.$key.'Var'}('var'));
			}

			$this->assertTrue($request->hasHttpReferer());
			$this->assertSame('referer', $request->getHttpReferer());
		}
	}
?>