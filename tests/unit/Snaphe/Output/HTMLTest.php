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
 * Tests for {@see Snaphe_Output_HTML}.
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
class Snaphe_Output_HTMLTest extends PHPUnit_Framework_TestCase
{
	
	/** @var Snaphe_Output_HTML */
	protected $output;

	public function setUp()
	{
		$this->output = new Snaphe_Output_HTML();
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
<html><head><title>Snaphe Result for FoobarWrapper</title></head><body><h1>Extracted data from <em>FoobarWrapper</em></h1><h2>URL(s)</h2> <ul>http://example.com</ul><h2>Input</h2><ul><li><strong>foo</strong> = "value 1"</li><li><strong>bar</strong> = "value 2"</li></ul><h2>Result</h2><ul><li><h3>Model 1:</h3><ol><li>"The Hitchhiker's Guide to the Galaxy"</li><li><ul><li><strong>0</strong> = "Science Fiction"</li><li><strong>1</strong> = "Comedy"</li></ul></li><li>1979</li><li>"Douglas Adams"</li></ol></li><li><h3>Model 2:</h3><ol><li><ul><li><strong>title</strong> = "In Rainbows"</li><li><strong>artist</strong> = "Radiohead"</li></ul></li><li><ul><li><strong>title</strong> = "Cassadaga"</li><li><strong>artist</strong> = "Bright Eyes"</li></ul></li><li><ul><li><strong>title</strong> = "The Resistance"</li><li><strong>artist</strong> = "Muse"</li></ul></li></ol></li></ul><p>Snaphe &copy; 2010 by <a href="http://florianeckerstorfer.com">Florian Eckerstorfer</a>.</p></body></html>
XML;
		$this->assertEquals($expectedResult, $this->output->render());
	}

	public function testRenderMultipleUrls()
	{
		$expectedResult = <<<XML
<html><head><title>Snaphe Result for FoobarWrapper</title></head><body><h1>Extracted data from <em>FoobarWrapper</em></h1><h2>URL(s)</h2> <ul><li><a href="http://example.com">http://example.com</a></li><li><a href="http://example.net">http://example.net</a></li></ul><h2>Input</h2><ul></ul><h2>Result</h2><ul></ul><p>Snaphe &copy; 2010 by <a href="http://florianeckerstorfer.com">Florian Eckerstorfer</a>.</p></body></html>
XML;

		$this->output->setName('FoobarWrapper')
					 ->setUrl(array('http://example.com', 'http://example.net'))
					 ->setInputParameters(array())
					 ->setModels(array())
		;

		$this->assertEquals($expectedResult, $this->output->render());
	}

}
