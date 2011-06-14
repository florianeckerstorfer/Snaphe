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
 * Tests for {@see Snaphe_Page_Form}.
 *
 * @package com.snaphe.page
 * @category tests
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Page_FormTest extends PHPUnit_Framework_TestCase
{
	
	/** @var Snaphe_Page_Form */
	protected $form;
	
	public function setUp()
	{
		$this->form = new Snaphe_Page_Form('index.php', 'post', array(
			array('name' => 'value1', 'type' => 'text', 'value' => 'foobar'),
			array('name' => 'value2', 'type' => 'password', 'value' => 'fOoBaR'),
			array('name' => 'value3', 'type' => 'select', 'options' => array('f' => 'foo', 'b' => 'bar'), 'value' => 'f'),
		));
	}
	
	public function tearDown()
	{
		$this->form = null;
	}
	
	public function testGetAction()
	{
		$this->assertEquals('index.php', $this->form->getAction());
	}
	
	public function testGetMethod()
	{
		$this->assertEquals('post', $this->form->getMethod());
	}
	
	public function testGetFields()
	{
		$expected = array(
			array('name' => 'value1', 'type' => 'text', 'value' => 'foobar'),
			array('name' => 'value2', 'type' => 'password', 'value' => 'fOoBaR'),
			array('name' => 'value3', 'type' => 'select', 'options' => array('f' => 'foo', 'b' => 'bar'), 'value' => 'f'),
		);
		$this->assertEquals($expected, $this->form->getFields());
	}
	
	public function testGetFieldNames()
	{
		$this->assertEquals(array('value1', 'value2', 'value3'), $this->form->getFieldNames());
	}
	
	public function testGetValues()
	{
		$expected = array(
			'value1'	=> 'foobar',
			'value2'	=> 'fOoBaR',
			'value3'	=> 'f'
		);
		$this->assertEquals($expected, $this->form->getValues());
	}
	
	public function testGetField()
	{
		$expected = array('name' => 'value1', 'type' => 'text', 'value' => 'foobar');
		$this->assertEquals($expected, $this->form->getField('value1'));
	}
	
	public function testGetValue()
	{
		$this->assertEquals('foobar', $this->form->getValue('value1'));
		$this->assertEquals('f', $this->form->getValue('value3'));
	}
	
	public function testGetType()
	{
		$this->assertEquals('text', $this->form->getType('value1'));
		$this->assertEquals('select', $this->form->getType('value3'));
	}
	
	public function testGetOptions()
	{
		$this->assertEquals(array('f' => 'foo', 'b' => 'bar'), $this->form->getOptions('value3'));
	}
	
}
