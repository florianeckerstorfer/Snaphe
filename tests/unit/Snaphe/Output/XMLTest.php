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
 * Tests for {@see Snaphe_Output_XML}.
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
class Snaphe_Output_XMLTest extends PHPUnit_Framework_TestCase
{
	
	/** @var Snaphe_Output_XML */
	protected $output;
	
	public function setUp()
	{
		$this->output = new Snaphe_Output_XML();
	}
	
	public function tearDown()
	{
		$this->output = null;
	}
	
	public function testRender()
	{
		$model1 = new Snaphe_Model();
		$model1->setValues(array(
			'title'		=> 'The Hitchhiker\'s Guide to the Galaxy',
			'genre'		=> array('Science Fiction', 'Comedy'),
			'year'		=> 1979,
			'author'	=> 'Douglas Adams',
		));
		$model2 = new Snaphe_Model();
		$model2->setValues(array(
			array(
				'title'		=> 'In Rainbows',
				'artist'	=> 'Radiohead',
			),
			array(
				'title'		=> 'Cassadaga',
				'artist'	=> 'Bright Eyes',
			),
			array(
				'title'		=> 'The Resistance',
				'artist'	=> 'Muse',
			)
		));
		$this->output->setName('FoobarWrapper')
					 ->setUrl('http://example.com')
					 ->setInputParameters(array('foo' => 'value 1', 'bar' => 'value 2'))
					 ->setModels(array($model1, $model2))
		;
		
		$expectedResult = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<wrapper>
 <name>FoobarWrapper</name>
 <url>http://example.com</url>
 <input>
  <key>foo</key>
  <value>value 1</value>
 </input>
 <input>
  <key>bar</key>
  <value>value 2</value>
 </input>
 <model>
  <data>
   <key>title</key>
   <value>The Hitchhiker's Guide to the Galaxy</value>
  </data>
  <data>
   <key>genre</key>
   <data>
    <key>0</key>
    <value>Science Fiction</value>
   </data>
   <data>
    <key>1</key>
    <value>Comedy</value>
   </data>
  </data>
  <data>
   <key>year</key>
   <value>1979</value>
  </data>
  <data>
   <key>author</key>
   <value>Douglas Adams</value>
  </data>
 </model>
 <model>
  <data>
   <key>0</key>
   <data>
    <key>title</key>
    <value>In Rainbows</value>
   </data>
   <data>
    <key>artist</key>
    <value>Radiohead</value>
   </data>
  </data>
  <data>
   <key>1</key>
   <data>
    <key>title</key>
    <value>Cassadaga</value>
   </data>
   <data>
    <key>artist</key>
    <value>Bright Eyes</value>
   </data>
  </data>
  <data>
   <key>2</key>
   <data>
    <key>title</key>
    <value>The Resistance</value>
   </data>
   <data>
    <key>artist</key>
    <value>Muse</value>
   </data>
  </data>
 </model>
</wrapper>

XML;
		$this->assertEquals($expectedResult, $this->output->render());
	}
	
	public function testRenderMultipleUrls()
	{
		$expectedResult = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<wrapper>
 <name>FoobarWrapper</name>
 <url>http://example.com</url>
 <url>http://example.net</url>
</wrapper>

XML;

		$this->output->setName('FoobarWrapper')
					 ->setUrl(array('http://example.com', 'http://example.net'))
					 ->setInputParameters(array())
					 ->setModels(array())
		;

		$this->assertEquals($expectedResult, $this->output->render());
	}
	
}
