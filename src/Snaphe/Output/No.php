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

/**
 * Dummy output class (can be used to ignore output).
 *
 * @package com.snaphe.output
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Output_No extends Snaphe_Output_Abstract
{
	
	/**
	 * Renders nothing!
	 *
	 * Example:
	 * <pre><code>
	 * $output = new Snaphe_Output_No();
	 * $output->setName('TestWapper')
	 *                 ->setUrl('http://example.com')
	 *                 ->setInputParameters(array('param1' => 'foobar', 'param2' => 42))
	 *                 ->setModels(array($model1, $model2));
	 * echo $output->render(); // output will be NULL!!
	 * </code></pre>
	 *
	 * @return NULL
	 */
	public function render() { return null; }
	
}
