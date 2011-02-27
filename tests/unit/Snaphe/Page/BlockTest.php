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
 * Tests for {@see Snaphe_Page_Block}.
 *
 * @package com.snaphe.page
 * @category tests
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Page_BlockTest extends PHPUnit_Framework_TestCase
{
	
	/** @var Snaphe_Page_Block */
	protected $block;
	
	public function setUp()
	{
		$this->block = new Snaphe_Page_Block('<div><h1>Hello World!</h1><p>foo<strong>bar</strong></p></div>');
	}
	
	public function tearDown()
	{
		$this->block = null;
	}
	
	public function testExtract()
	{
		// All models return TRUE.
		$model1 = $this->getMock('Snaphe_Model_Interface');
		$model1->expects($this->any())
			   ->method('extract')
			   ->will($this->returnValue(true));
		$model2 = $this->getMock('Snaphe_Model_Interface');
		$model2->expects($this->any())
			   ->method('extract')
			   ->will($this->returnValue(true));
		$this->assertTrue($this->block->extract(array($model1, $model2)));
		
		// Some models return false.
		$model1 = $this->getMock('Snaphe_Model_Interface');
		$model1->expects($this->any())
			   ->method('extract')
			   ->will($this->returnValue(true));
		$model2 = $this->getMock('Snaphe_Model_Interface');
		$model2->expects($this->any())
			   ->method('extract')
			   ->will($this->returnValue(false));
		$this->assertFalse($this->block->extract(array($model1, $model2)));
	}
	
}
