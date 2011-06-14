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
 * Tests for {@see Snaphe_Wrapper_Abstract}.
 *
 * @package com.snaphe.wrapper
 * @category tests
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Wrapper_AbstractTest extends PHPUnit_Framework_TestCase
{
	
	/** @var MySnaphe_Wrapper_Abstract */
	protected $wrapper;
	
	public function setUp()
	{
		$this->wrapper = new MySnaphe_Wrapper_Abstract(array('foo' => 'value foobar'));
	}
	
	public function tearDown()
	{
		$this->wrapper = null;
	}
	
	public function testInitializeParameters()
	{
		$this->assertEquals('value foobar', $this->wrapper->getParameter('foo'));
		$this->assertNull($this->wrapper->getParameter('bar'));
	}
	
	/**
	 * @expectedException Snaphe_Exception
	*/
	public function testInitializeParameters_MissingParameters() 
	{
	    $c = new MySnaphe_Wrapper_Abstract(array());
	}
	
	public function testHasParameter()
	{
		$this->assertFalse($this->wrapper->hasParameter('foobar'));
		$this->assertNull($this->wrapper->getParameter('foobar'));
	}
	
	/**
	 * @expectedException Snaphe_Exception
	*/
	public function testSetParameter_NotDefined() 
	{
		$this->wrapper->setParameter('foobar', 'value');
	}
	
	public function testDefineParameter()
	{
		$this->wrapper->myDefineParameter('foobar');
		$this->wrapper->setParameter('foobar', 'value');
		$this->assertTrue($this->wrapper->hasParameter('foobar'));
		$this->assertEquals('value', $this->wrapper->getParameter('foobar'));
	}
	
}

class MySnaphe_Wrapper_Abstract extends Snaphe_Wrapper_Abstract
{
	
	protected $definedParameters = array('foo', 'bar');
	protected $requiredParameters = array('foo');
	
	public function __construct($parameters)
	{
		$this->initializeParameters($parameters);
	}
	
	public function myDefineParameter($key)
	{
		return $this->defineParameter($key);
	}
	
	public function execute() {}
	
}
