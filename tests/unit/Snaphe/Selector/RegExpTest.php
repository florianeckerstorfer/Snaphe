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
 * Tests for {@see Snaphe_Selector_RegExp}.
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
class Snaphe_Selector_RegExpTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * @dataProvider dataProviderExtract
	 */
	public function testExtract($input, $regExp, $matchPosition, $expected)
	{
		$s = new Snaphe_Selector_RegExp($regExp, $matchPosition);
		$this->assertEquals($expected, $s->extract($input));
	}
	
	public function dataProviderExtract()
	{
		return array(
			// No result.
			array(
				'<body>Hello World!</body>',
				'/<strong>(.*)<\/strong>/', 1,
				null
			),
			// Single result.
			array(
				'<body>Hello <strong>World</strong>!</body>',
				'/<strong>(.*)<\/strong>/', 1,
				'World'
			),
			// Single result with match position.
			array(
				'<body>Hello <strong style="font-weight:bold;">World</strong>!</body>',
				'/<strong(.*)>(.*)<\/strong>/', 2,
				'World'
			),
			// No result with match position.
			array(
				'<body>Hello <strong>World</strong>!</body>',
				'/<strong>(.*)<\/strong>/', 2,
				null
			),
			// Multiple results.
			array(
				'<body>Hello <strong>World</strong>! Hello <strong>Foobar</strong>!</body>',
				'/<strong>(.*)<\/strong>/U', 1,
				array('World', 'Foobar')
			),
			// Multiple results with match position.
			array(
				'<body>Hello <strong style="font-weight:bold;">World</strong>! Hello <strong style="font-weight:bold;">Foobar</strong>!</body>',
				'/<strong(.*)>(.*)<\/strong>/U', 2,
				array('World', 'Foobar')
			),
			// Multiple results but with wrong match position. (= null result)
			array(
				'<body>Hello <strong>World</strong>! Hello <strong>Foobar</strong>!</body>',
				'/<strong>(.*)<\/strong>/U', 2,
				null
			),
			// Multiple match positions.
			array(
				'<body>Hello <strong class="foo">World</strong>!</body>',
				'/<strong class="(.*)">(.*)<\/strong>/U', array(1, 2),
				array(array('foo', 'World'))
			),
			array(
				'<body>Hello <strong>World</strong>! Hello <strong class="foo">World</strong>!</body>',
				'/<strong class="(.*)">(.*)<\/strong>/U', array(1, 2),
				array(array('foo', 'World'))
			),
			// Multiple results and multiple match positions.
			array(
				'<body>Hello <strong class="foo">World</strong>! Hello <strong class="foo">World</strong>!</body>',
				'/<strong class="(.*)">(.*)<\/strong>/U', array(1, 2),
				array(array('foo', 'World'), array('foo', 'World'))
			),
		);
	}
	
}
