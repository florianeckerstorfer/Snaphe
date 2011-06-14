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
 * Tests for {@see Snaphe_Output_Abstract}.
 *
 * @package com.snaphe.output
 * @category tests
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Output_AbstractTest extends PHPUnit_Framework_TestCase
{
	
	/** @var MySnaphe_Output_Abstract */
	protected $output;
	
	public function setUp()
	{
		$this->output = new MySnaphe_Output_Abstract();
	}
	
	public function tearDown()
	{
		$this->output = null;
	}
	
	public function testSetWrapperName()
	{
		$this->output->setName('Foobar');
		$this->assertEquals('Foobar', $this->output->getName());
	}
	
	public function testSetUrl()
	{
		$this->output->setUrl('http://example.com');
		$this->assertEquals('http://example.com', $this->output->getUrl());
	}
	
	public function testSetInputParameters()
	{
		$this->output->setInputParameters(array('foo' => 'value1', 'bar' => 'value2'));
		$this->assertEquals(array('foo' => 'value1', 'bar' => 'value2'), $this->output->getInputParameters());
	}
	
}

class MySnaphe_Output_Abstract extends Snaphe_Output_Abstract
{
	
	public function render()
	{
	}
	
}
