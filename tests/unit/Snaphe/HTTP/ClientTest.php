<?php

/*
 * The MIT License
 * 
 * Copyright (c) 2011 Florian Eckerstorfer
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once 'PHPUnit/Framework.php';

/**
 * Tests for {@see Snaphe_HTTP_Client}.
 *
 * @package com.snaphe.http
 * @category tests
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_HTTP_ClientTest extends PHPUnit_Framework_TestCase
{

	/** @var Snaphe_HTTP_Client */
	protected $client;
	
	public function setUp()
	{
		$this->client = new Snaphe_HTTP_Client();
	}
	
	public function tearDown()
	{
		$this->client = null;
	}
	
	public function testExecute()
	{
		$result = $this->client->executeGet('http://echo.pckg.org');
		$this->assertThat($result, $this->isInstanceOf('Snaphe_HTTP_Response'));
	}
	
	public function testExecuteGet()
	{
		$result = $this->client->executeGet('http://echo.pckg.org', array('p1' => 'foo', 'p2' => 'bar'));
		$this->assertRegExp('/\[p1\] => foo/', $result->getBody());
		$this->assertRegExp('/\[p2\] => bar/', $result->getBody());
	}
	
	public function testExecutePost()
	{
		$result = $this->client->executePost('http://echo.pckg.org', array('p1' => 'foo', 'p2' => 'bar'));
		$this->assertRegExp('/\[p1\] => foo/', $result->getBody());
		$this->assertRegExp('/\[p2\] => bar/', $result->getBody());
	}
	
	public function testSetUserAgent()
	{
		$this->client->setUserAgent('cURLClientTest');
		$result = $this->client->executeGet('http://echo.pckg.org');
		$this->assertEquals('cURLClientTest', $this->client->getUserAgent());
		$this->assertRegExp('/User-Agent: cURLClientTest/', $result->getBody());
	}
	
	public function testSetReferer()
	{
		$this->client->setReferer('http://example.com');
		$result = $this->client->executeGet('http://echo.pckg.org');
		$this->assertEquals('http://example.com', $this->client->getReferer());
		$this->assertRegExp('/Referer: http:\/\/example.com/', $result->getBody());
	}
	
	public function testSetEncoding()
	{
		$this->client->setEncoding('gzip');
		$result = $this->client->executeGet('http://echo.pckg.org');
		$this->assertEquals('gzip', $this->client->getEncoding());
		$this->assertRegExp('/Accept-Encoding: gzip/', $result->getBody());
	}
	
	public function testSetHeaders()
	{
		$this->client->setHeaders(array('HTTP_ACCEPT_LANGUAGE' => 'de-DE'));
		$result = $this->client->executeGet('http://echo.pckg.org');
		$this->assertEquals(array('HTTP_ACCEPT_LANGUAGE' => 'de-DE'), $this->client->getHeaders());
		$this->assertRegExp('/HTTP_ACCEPT_LANGUAGE: de-DE/', $result->getBody());
	}
	
	public function testAddHeader()
	{
		$this->client->addHeader('CUSTOM_HEADER', 'foobar');
		$result = $this->client->executeGet('http://echo.pckg.org');
		$this->assertEquals('foobar', $this->client->getHeader('CUSTOM_HEADER'));
		$this->assertRegExp('/CUSTOM_HEADER: foobar/', $result->getBody());
	}
	
	public function setCookies()
	{
		$this->client->setCookies(array('foobar' => 'Hello World!'));
		$result = $this->client->executeGet('http://echo.pckg.org');
		$this->assertEquals(array('foobar' => 'Hello World!'), $this->client->getCookies());
		$this->assertRegExp('/\[foobar\] => Hello World!/', $result->getBody());
	}
	
	public function addCookie()
	{
		$this->client->addCookie('newbar', 'foobar');
		$result = $this->client->executeGet('http://echo.pckg.org');
		$this->assertEquals('foobar', $this->client->getCookie('newbar'));
		$this->assertRegExp('/\[newbar\] => foobar/', $result->getBody());
	}
	
	public function testProxyServers()
	{
		$this->client->addProxyServer('141.76.45.17', 3124, 'foo');
		$this->assertEquals(array('141.76.45.17', 3124, 'foo', null, null), $this->client->getProxyServer('foo'));
		$this->assertEquals(array('141.76.45.17', 3124, 'foo', null, null), $this->client->getRandomProxyServer());
		$this->client->addProxyServer('141.76.45.17', 3124, 'helloworld', 'foo', 'bar');
		$this->assertEquals(array('141.76.45.17', 3124, 'helloworld', 'foo', 'bar'), $this->client->getProxyServer('helloworld'));
	}
	
}
