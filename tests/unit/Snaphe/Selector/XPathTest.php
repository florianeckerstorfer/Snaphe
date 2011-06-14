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
 * Tests for {@see Snaphe_Selector_XPath}.
 *
 * @package com.snaphe.selector
 * @category tests
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Selector_XPathTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * @dataProvider dataProviderExtract
	 */
	public function testExtract($input, $xpath, $expected)
	{
		$s = new Snaphe_Selector_XPath($xpath);
		$this->assertEquals($expected, $s->extract($input));
	}
	
	public function dataProviderExtract()
	{
		return array(
			// Single result.
			array(
				'<body>Hello <strong>World</strong>!</body>',
				'//strong',
				'World'
			),
			// Multiple results.
			array(
				'<body>Hello <strong>World</strong>! Hello <strong>Foobar</strong>!</body>',
				'//strong',
				array('World', 'Foobar')
			),
			// A result that contains sub tags.
			array(
				'<body><p><em>Hello </em><strong>World!</strong></p></body>',
				'//p',
				'<p><em>Hello </em><strong>World!</strong></p>'
			),
			// Multiple results and results that contain sub tags.
			array(
				'<body><p><em>Hello </em><strong>World!</strong></p><p><em>Hello </em><strong>World!</strong></p></body>',
				'//p',
				array('<p><em>Hello </em><strong>World!</strong></p>', '<p><em>Hello </em><strong>World!</strong></p>')
			),
			// No result.
			array(
				'<body>Hello World!</body>',
				'//strong',
				null
			),
		);
	}
	
}
