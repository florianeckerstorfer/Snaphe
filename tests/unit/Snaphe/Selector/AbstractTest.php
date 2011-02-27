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
 * Tests for {@see Snaphe_Selector_Abstract}.
 *
 * @package com.snaphe.selector
 * @category tests
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Selector_AbstractTest extends PHPUnit_Framework_TestCase
{
	
	public function testRegisterCallback_XPath()
	{
		$s = new Snaphe_Selector_XPath('//strong');
		$s->registerCallback('strtoupper');
		$this->assertEquals('WORLD', $s->extract('<body>Hello <strong>World</strong>!</body>'));
	}
	
	public function testRegisterCallback_CSS()
	{
		$s = new Snaphe_Selector_CSS('strong');
		$s->registerCallback('strtoupper');
		$this->assertEquals('WORLD', $s->extract('<body>Hello <strong>World</strong>!</body>'));
	}
	
	public function testRegisterCallback_RegExp()
	{
		$s = new Snaphe_Selector_RegExp('/<strong>(.*)<\/strong>/');
		$s->registerCallback('strtoupper');
		$this->assertEquals('WORLD', $s->extract('<body>Hello <strong>World</strong>!</body>'));
	}
	
}
