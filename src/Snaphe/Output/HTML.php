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
 * Generates output in a human readable format in HTML.
 *
 * @package com.snaphe.output
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Output_HTML extends Snaphe_Output_Abstract
{

	/**
	 * Renders the output class in HTML format.
	 *
	 * Example:
	 * <pre><code>
	 * $output = new Snaphe_Output_HMTL();
	 * $output->setName('TestWrapper')
	 *                 ->setUrl('http://example.com')
	 *                 ->setInputParameters(array('param1' => 'foobar', 'param2' => 42))
	 *                 ->setModels(array($model1, $model2));
	 * echo $output->render();
	 * </code></pre>
	 *
	 * @return string HTML formatted output.
	 */
	public function render()
	{
		$output = '<html><head><title>Snaphe Result for ' . $this->getName() . '</title></head><body>';
		$output .= '<h1>Extracted data from <em>' . $this->getName() . '</em></h1>';
		$output .= '<h2>URL(s)</h2> <ul>' . $this->renderUrls($this->getUrl()) . '</ul>';
		
		$output .= '<h2>Input</h2><ul>';
		foreach ($this->getInputParameters() as $key => $value)
		{
			$output .= '<li><strong>' . $key . '</strong> = ' . $this->renderValue($value) . '</li>';
		}
		$output .= '</ul>';
		
		$output .= '<h2>Result</h2><ul>';
		foreach ($this->getModels() as $modelIndex => $model)
		{
			$modelTitle = '<li><h3>Model ' . (++$modelIndex) . ':';
			$output .= $modelTitle . '</h3>';
			
			if (!($model instanceof Snaphe_Model_Interface))
			{
				$output .= "Invalid model\n\n";
				continue;
			}
			$output .= '<ol>';
			foreach ($model->getValues() as $key => $value)
			{
				$output .= '<li>';
				if (is_array($value))
				{
					$output .= $this->renderValue($value, 2);
				}
				else
				{
					$output .= $this->renderValue($value);
				}
				$output .= '</li>';
			}
			
			$output .= '</ol></li>';
		}
		$output .= '</ul><p>Snaphe &copy; 2010 by <a href="http://florianeckerstorfer.com">Florian Eckerstorfer</a>.</p></body></html>';
		return $output;
	}

	protected function renderValue($value, $level = 0)
	{
		$prefix = '';
		if ($level > 0)
		{
			$prefix = "\n" . str_repeat(' ', $level * 4) . '- ';
		}
		if (is_array($value))
		{
			$return = '<ul>';
			foreach ($value as $valueKey => $valueValue)
			{
				$return .= '<li><strong>' . $valueKey . '</strong> = '
					. $this->renderValue($valueValue, is_array($valueValue) ? $level+1 : 0) . '</li>';
			}
			$return .= '</ul>';
			return $return;
		}
		elseif (is_string($value))
		{
			return $prefix . '"'  . $value . '"';
		}
		return $prefix . $value;
	}

	protected function renderUrls($urls)
	{
		if (is_array($urls))
		{
			$result = '';
			foreach ($urls as $url)
			{
				$result .= '<li><a href="' . $url . '">' . $url . '</a></li>';
			}
			return $result;
		}
		return $urls;
	}

}
