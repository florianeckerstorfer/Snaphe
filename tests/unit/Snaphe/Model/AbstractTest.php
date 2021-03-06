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
 * Tests for {@see Snaphe_Model_Abstract}.
 *
 * @package com.snaphe.model
 * @category tests
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Model_AbstractTest extends PHPUnit_Framework_TestCase
{
	
	/** @var Snaphe_Model */
	protected $model;
	
	public function setUp()
	{
		$this->model = new Snaphe_Model();
	}
	
	public function tearDown()
	{
		$this->model = null;
	}
	
	public function testAddValue()
	{
		$selector1 = $this->getMock('Snaphe_Selector_Interface');
		$selector2 = $this->getMock('Snaphe_Selector_Interface');
		$this->model->addValue('value1', $selector1);
		$this->model->addValue('value2', $selector2);
		$this->assertEquals($selector1, $this->model->getSelector('value1'));
		$this->assertEquals($selector2, $this->model->getSelector('value2'));
	}
	
	public function testSetValues()
	{
		$this->model->setValues(array('value1' => 'foo', 'value2' => 'bar'));
		$this->assertEquals(array('value1' => 'foo', 'value2' => 'bar'), $this->model->getValues());
	}
	
	public function testSetValue()
	{
		$this->model->setValue('value1', 'foo');
		$this->assertEquals('foo', $this->model->getValue('value1'));
	}
	
}
