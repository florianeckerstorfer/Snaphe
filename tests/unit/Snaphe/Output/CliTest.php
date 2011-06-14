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
 * Tests for {@see Snaphe_Output_Cli}.
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
class Snaphe_Output_CliTest extends PHPUnit_Framework_TestCase
{
	
	/** @var Snaphe_Output_Cli */
	protected $output;
	
	public function setUp()
	{
		$this->output = new Snaphe_Output_Cli();
	}
	
	public function tearDown()
	{
		$this->output = null;
	}
	
	public function testRender()
	{
		$model1 = $this->getMock('Snaphe_Model_Interface');
		$model1->expects($this->any())
			   ->method('getValues')
			   ->will($this->returnValue(array(
				'title'		=> 'The Hitchhiker\'s Guide to the Galaxy',
				'genre'		=> array('Science Fiction', 'Comedy'),
				'year'		=> 1979,
				'author'	=> 'Douglas Adams',
		)));
		$model2 = $this->getMock('Snaphe_Model_Interface');
		$model2->expects($this->any())
			   ->method('getValues')
			   ->will($this->returnValue(array(
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
		)));
		$this->output->setName('FoobarWrapper')
					 ->setUrl('http://example.com')
					 ->setInputParameters(array('foo' => 'value 1', 'bar' => 'value 2'))
					 ->setModels(array($model1, $model2));
		$result = $this->output->render();

		$expectedResult = <<<PLAIN
Extracted data from "FoobarWrapper"
===================================
URL:
    http://example.com
Input:
    - foo = "value 1"
    - bar = "value 2"

Model 1:
--------
    - title = "The Hitchhiker's Guide to the Galaxy"
    - genre = 
        - 0 = "Science Fiction"
        - 1 = "Comedy"
    - year = 1979
    - author = "Douglas Adams"

Model 2:
--------
    - 0 = 
        - title = "In Rainbows"
        - artist = "Radiohead"
    - 1 = 
        - title = "Cassadaga"
        - artist = "Bright Eyes"
    - 2 = 
        - title = "The Resistance"
        - artist = "Muse"


PLAIN;

		// $this->assertEquals(strlen($epxectedResult), strlen($result));
		$this->assertEquals($expectedResult, $result);
		
		// With multiple URLs.
		$expectedResult = <<<PLAIN
Extracted data from "FoobarWrapper"
===================================
URL:
    - http://example.com
    - http://example.net
Input:


PLAIN;

		$this->output->setName('FoobarWrapper')
					 ->setUrl(array('http://example.com', 'http://example.net'))
					 ->setInputParameters(array())
					 ->setModels(array())
		;

		$this->assertEquals($expectedResult, $this->output->render());
	}
	
}
