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
 * Generates output in a text format.
 *
 * @package com.snaphe.output
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Output_Text extends Snaphe_Output_Abstract
{

	/**
	 * Renders the output class in a text format.
	 *
	 * Example:
	 * <pre><code>
	 * $output = new Snaphe_TextOutput();
	 * $output->setName('TestWrapper')
	 *                 ->setUrl('http://example.com')
	 *                 ->setInputParameters(array('param1' => 'foobar', 'param2' => 42))
	 *                 ->setModels(array($model1, $model2));
	 * echo $output->render();
	 * </code></pre>
	 *
	 * @return string Text formatted output.
	 */
	public function render()
	{
		$output = 'name:' . $this->getName() . "\n";
		$urls = is_array($this->getUrl()) ? $this->getUrl() : array($this->getUrl());
		$output .= 'url:' . http_build_query($urls) . "\n";
		$output .= 'input:' . http_build_query($this->getInputParameters()) . "\n";

		foreach ($this->getModels() as $modelIndex => $model)
		{
			$output .= 'model' . (++$modelIndex) . ':';

			if (!($model instanceof Snaphe_Model_Interface))
			{
				$output .= "Invalid model\n\n";
				continue;
			}
			$output .= http_build_query($model->getValues()) . "\n";
		}
		return $output . "\n";
	}

}
