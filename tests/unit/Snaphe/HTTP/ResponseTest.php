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

/**
 * Tests for {@see Snaphe_HTTP_Response}.
 *
 * @package com.snaphe.http
 * @category tests
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_HTTP_ResponseTest extends PHPUnit_Framework_TestCase
{

	public function testConstruct()
	{
		$body = <<<HTML
HTTP/1.1 200 OK
Date: Wed, 11 Aug 2010 08:35:39 GMT
Server: Apache
X-Powered-By: PHP/5.2.4-2ubuntu5.5
Set-Cookie: foo=foobar
Set-Cookie: foo2=foobar2
Content-Length: 1808
Content-Type: text/html\r\n\r\n<!DOCTYPE html>
<html>
<head><title>HTTP Echo</title></head>
<body><h1>HTTP Echo</h1></body>
</html>
HTML;
		$info = array(
			'url'                       => '',
			'content_type'              => 'text/html',
			'http_code'                 => '200',
			'header_size'               => '',
			'request_size'              => '',
			'filetime'                  => '',
			'ssl_verify_result'         => '',
			'redirect_count'            => '',
			'total_time'                => '',
			'namelookup_time'           => '',
			'connect_time'              => '',
			'pretransfer_time'          => '',
			'size_upload'               => '',
			'size_download'             => '',
			'speed_download'            => '',
			'speed_upload'              => '',
			'download_content_length'	=> '',
			'upload_content_length'     => '',
			'starttransfer_time'        => '',
			'redirect_time'             => '',
		);

		$expectedResponse = <<<HTML
<!DOCTYPE html>
<html>
<head><title>HTTP Echo</title></head>
<body><h1>HTTP Echo</h1></body>
</html>
HTML;

		$r = new Snaphe_HTTP_Response($body, $info);
		$this->assertEquals('Wed, 11 Aug 2010 08:35:39 GMT', $r->getHeader('Date'));
		$this->assertEquals('text/html', $r->getHeader('Content-Type'));
		$this->assertEquals('foobar', $r->getCookie('foo'));
		$this->assertEquals(array('foo' => 'foobar', 'foo2' => 'foobar2'), $r->getCookies());
		$this->assertEquals($expectedResponse, $r->getBody());
		$this->assertEquals('200', $r->getInfo('http_code'));
		$this->assertEquals('text/html', $r->getInfo('content_type'));
	}
	
}
