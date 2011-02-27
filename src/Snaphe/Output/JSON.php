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
 * Generates output in JSON.
 *
 * @package com.snaphe.output
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Output_JSON extends Snaphe_Output_Abstract
{

	/**
	 * Renders the output class in JSON format.
	 *
	 * Example:
	 * <pre><code>
	 * $output = new Snaphe_Output_JSON();
	 * $output->setName('TestWrapper')
	 *                 ->setUrl('http://example.com')
	 *                 ->setInputParameters(array('param1' => 'foobar', 'param2' => 42))
	 *                 ->setModels(array($model1, $model2));
	 * echo $output->render();
	 * </code></pre>
	 *
	 * @return string JSON formatted output.
	 */
	public function render()
	{
		$result = array();
		foreach ($this->getModels() as $modelIndex => $model)
		{
			$result[] = $model->getValues();
		}
		$output = array(
			'title'		=> $this->getName(),
			'urls'		=> $this->getUrl(),
			'input'		=> $this->getInputParameters(),
			'result'	=> $result,
		);
		return json_encode($output);
	}

}
