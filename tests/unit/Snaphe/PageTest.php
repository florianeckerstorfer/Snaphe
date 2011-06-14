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

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once 'PHPUnit/Framework.php';

/**
 * Tests for {@see Snaphe_Page}.
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
class Snaphe_PageTest extends PHPUnit_Framework_TestCase
{
	
	/** @var Snaphe_Page */
	protected $page;
	
	public function setUp()
	{
		$this->page = new Snaphe_Page('http://example.com');
		$this->page->setCURLClient($this->getMock('Snaphe_HTTP_Client'));
	}
	
	public function tearDown()
	{
		$this->page = null;
	}
	
	public function testExtract()
	{
		// All models return TRUE.
		$response = 'foobar';
		$client = $this->getMock('Snaphe_HTTP_Client');
		$client->expects($this->any())
			   ->method('executeGet')
			   ->will($this->returnValue(new Snaphe_HTTP_Response($response)));
		$model1 = $this->getMock('Snaphe_Model_Interface');
		$model1->expects($this->any())
			   ->method('extract')
			   ->will($this->returnValue(true));
		$model2 = $this->getMock('Snaphe_Model_Interface');
		$model2->expects($this->any())
			   ->method('extract')
			   ->will($this->returnValue(true));
		$this->page->setCurlClient($client);
		$this->assertEquals(true, $this->page->extract(array($model1, $model2), array(), false));
		
		// Some models return FALSE.
		$response = 'foobar';
		$client = $this->getMock('Snaphe_HTTP_Client');
		$client->expects($this->any())
			   ->method('executeGet')
			   ->will($this->returnValue(new Snaphe_HTTP_Response($response)));
		$model1 = $this->getMock('Snaphe_Model_Interface');
		$model1->expects($this->any())
			   ->method('extract')
			   ->will($this->returnValue(true));
		$model2 = $this->getMock('Snaphe_Model_Interface');
		$model2->expects($this->any())
			   ->method('extract')
			   ->will($this->returnValue(false));
		$this->page->setCurlClient($client);
		$this->assertEquals(false, $this->page->extract(array($model1, $model2), array(), false));
	}
	
	public function testExtractBlocks()
	{
		$response = '<div><h1>Hello World!</h1><p>foo<strong>bar</strong>. <em>foo</em>bar.</p></div>';
		$client = $this->getMock('Snaphe_HTTP_Client');
		$client->expects($this->any())
			   ->method('executeGet')
			   ->will($this->returnValue(new Snaphe_HTTP_Response($response)));
		$this->page->setCurlClient($client);
		$result = $this->page->extractBlocks(new Snaphe_Selector_XPath('/div/p'));
		$this->assertTrue($result[0] instanceof Snaphe_Page_Block);
	}
	
	public function testExtractForms()
	{
			$response = <<<HTML
<body>
	<h1>Hello World!</h1>
	<form action="index.php" method="post">
		<input type="button" name="buttonfield" value="foobar button" />
		<input type="checkbox" name="checkboxfield" value="foobar checkbox" />
		<input type="file" name="filefield" value="foobar file" />
		<input type="hidden" name="hiddenfield" value="foobar hidden" />
		<input type="image" name="imagefield" value="foobar image" />
		<input type="radio" name="radiofield" value="foobar radio" />
		<input type="reset" name="resetfield" value="foobar reset" />
		<input type="submit" name="submitfield" value="foobar submit" />
		<input type="text" name="textfield" value="foobar textfield" />
		<select name="selectfield">
			<option value="foo" selected="selected">Foo</option>
			<option value="bar">Bar</option>
		</select>
		<select name="selectfield2">
			<option selected="selected" value="foo">Foo</option>
			<option value="bar">Bar</option>
		</select>
	</form>
</body>
HTML;
			$client = $this->getMock('Snaphe_HTTP_Client');
			$client->expects($this->any())
				   ->method('executeGet')
				   ->will($this->returnValue(new Snaphe_HTTP_Response($response)));
			$this->page->setCurlClient($client);
			$forms = $this->page->extractForms();
			$this->assertEquals('index.php', $forms[0]->getAction());
			$this->assertEquals('post', $forms[0]->getMethod());
			$this->assertEquals(array('type' => 'button', 'name' => 'buttonfield', 'value' => 'foobar button'), 
					$forms[0]->getField('buttonfield'));
			$this->assertEquals('foobar checkbox', $forms[0]->getValue('checkboxfield'));
			$this->assertEquals('file', $forms[0]->getType('filefield'));
			$this->assertEquals(array('buttonfield', 'checkboxfield', 'filefield', 'hiddenfield', 'imagefield',
							'radiofield', 'resetfield', 'submitfield', 'textfield', 'selectfield', 'selectfield2'),
					$forms[0]->getFieldNames());
			$this->assertEquals(11, count($forms[0]->getFields()));
			$values = $forms[0]->getValues();
			$this->assertEquals('foobar hidden', $values['hiddenfield']);
			$this->assertEquals(array('foo' => 'Foo', 'bar' => 'Bar'), $forms[0]->getOptions('selectfield'));
			$this->assertEquals('foo', $forms[0]->getValue('selectfield'));
			$this->assertEquals('foo', $forms[0]->getValue('selectfield2'));
			
			// Extract select boxes without closing option tag
			$response = <<<HTML
<body>
	<h1>Hello World!</h1>
	<form action="index.php" method="post">
        <select name="selectfield">
        	<option value="foo" selected>Foo
       	<option value="bar">Bar
        </select>
	</form>
</body>
HTML;
			$client = $this->getMock('Snaphe_HTTP_Client');
			$client->expects($this->any())
	   			   ->method('executeGet')
				   ->will($this->returnValue(new Snaphe_HTTP_Response($response)));
			$this->page->setCurlClient($client);
			$forms = $this->page->extractForms();
			$this->assertEquals(array('foo' => 'Foo', 'bar' => 'Bar'), $forms[0]->getOptions('selectfield'));
			$this->assertEquals('foo', $forms[0]->getValue('selectfield'));
			
			// Extract select boxes without multiple selected options
			$response = <<<HTML
<body>
	<h1>Hello World!</h1>
	<form action="index.php" method="post">
		<select name="selectfield">
			<option value="foo" selected>Foo
			<option value="bar">Bar
			<option value="foobar" selected>Foobar
		</select>
	</form>
</body>
HTML;
			$client = $this->getMock('Snaphe_HTTP_Client');
			$client->expects($this->any())
				   ->method('executeGet')
				   ->will($this->returnValue(new Snaphe_HTTP_Response($response)));
			$this->page->setCurlClient($client);
			$forms = $this->page->extractForms();
			$this->assertEquals(array('foo', 'foobar'), $forms[0]->getValue('selectfield'));
			
			// Extract input values when attributes are missing
			$response = <<<HTML
<body>
	<h1>Hello World!</h1>
	<form action="index.php" method="post">
		<input name="buttonfield" value="foobar button" />
		<input type="checkbox" name="checkboxfield" />
		<input value="foobar file" name="filefield" />
		<input name="hiddenfield" type="hidden" value="foobar hidden" />
	</form>
</body>
HTML;
			$client = $this->getMock('Snaphe_HTTP_Client');
			$client->expects($this->any())
				   ->method('executeGet')
				   ->will($this->returnValue(new Snaphe_HTTP_Response($response)));
			$this->page->setCurlClient($client);
			$forms = $this->page->extractForms();
			$this->assertEquals('foobar button', $forms[0]->getValue('buttonfield'));
			$this->assertEquals(null, $forms[0]->getValue('checkboxfield'));
			$this->assertEquals('foobar file', $forms[0]->getValue('filefield'));
			$this->assertEquals('foobar hidden', $forms[0]->getValue('hiddenfield'));
	}
	
}
