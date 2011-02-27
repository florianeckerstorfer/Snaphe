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
 * Tests for {@see Snaphe_Application_Abstract}.
 *
 * @package com.snaphe.applicaton
 * @category tests
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Application_AbstractTest extends PHPUnit_Framework_TestCase
{
	
	/** @var MyCLITool */
	protected $cli;
	
	/** @var string */
	protected $fixtures_dir;
	
	public function setUp()
	{
		$this->fixtures_dir = realpath(dirname(dirname(dirname(dirname(__FILE__)))) . '/fixtures/wrappers');
		$this->cli = new MyCLITool($this->fixtures_dir);
//		$this->cli->run(array('snaphe', '--wrapper=Foobar', '--format=no'));
	}
	
	public function tearDown()
	{
		$this->fixtures_dir = null;
		$this->cli = null;
	}
	
	public function testGetWrappers()
	{
		$wrappers = $this->cli->myGetWrappers();
		$this->assertRegExp('/BarWrapper/', $wrappers[0]);
		$this->assertRegExp('/FoobarWrapper/', $wrappers[1]);
		$this->assertRegExp('/FooWrapper/', $wrappers[2]);
	}
	
	public function testGetWrapperName()
	{
		$this->assertEquals('FoobarWrapper', $this->cli->myGetWrapperName('/usr/var/wrappers/FoobarWrapper.php'));
	}
	
	public function testGetWrapperFilename()
	{
		$this->assertEquals($this->fixtures_dir . '/FoobarWrapper.php', $this->cli->myGetWrapperFilename('FoobarWrapper'));
	}
	
}

class MyCLITool extends Snaphe_Application_Abstract
{

	public function myGetWrappers()
	{
		return $this->getWrappers();
	}
	
	public function myGetWrapperName($wrapperFilename)
	{
		return $this->getWrapperName($wrapperFilename);
	}
	
	public function myGetWrapperFilename($wrapperName)
	{
		return $this->getWrapperFilename($wrapperName);
	}

}
