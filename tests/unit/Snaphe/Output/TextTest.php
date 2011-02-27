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
 * Tests for {@see Snaphe_Output_Text}.
 *
 * @package com.snaphe.output
 * @category tests
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Output_TextTest extends PHPUnit_Framework_TestCase
{
	
	/** @var Snaphe_Output_Text */
	protected $output;
	
	public function setUp()
	{
		$this->output = new Snaphe_Output_Text();
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
			$result = $this->output->render();

			$expectedResult = <<<PLAIN
name:FoobarWrapper
url:0=http%3A%2F%2Fexample.com
input:foo=value+1&bar=value+2
model1:title=The+Hitchhiker%27s+Guide+to+the+Galaxy&genre%5B0%5D=Science+Fiction&genre%5B1%5D=Comedy&year=1979&author=Douglas+Adams
model2:0%5Btitle%5D=In+Rainbows&0%5Bartist%5D=Radiohead&1%5Btitle%5D=Cassadaga&1%5Bartist%5D=Bright+Eyes&2%5Btitle%5D=The+Resistance&2%5Bartist%5D=Muse


PLAIN;

			$this->assertEquals(strlen($expectedResult), strlen($result));
			$this->assertEquals($expectedResult, $result);
	}
	
	public function testRenderWithMultipleUrls()
	{
			$expectedResult = <<<PLAIN
name:FoobarWrapper
url:0=http%3A%2F%2Fexample.com&1=http%3A%2F%2Fexample.net
input:


PLAIN;

			$this->output->setName('FoobarWrapper')
						 ->setUrl(array('http://example.com', 'http://example.net'))
						 ->setInputParameters(array())
						 ->setModels(array())
			;

			$this->assertEquals($expectedResult, $this->output->render());
	}
	
}
