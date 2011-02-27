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
 * Generates output in a human readable format optimized for the command line interface.
 *
 * @package com.snaphe.output
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Output_Cli extends Snaphe_Output_Abstract
{

	/**
	 * Renders the output class in CLI format.
	 *
	 * Example:
	 * <pre><code>
	 * $output = new Snaphe_Output_Cli();
	 * $output->setName('TestWrapper')
	 *                 ->setUrl('http://example.com')
	 *                 ->setInputParameters(array('param1' => 'foobar', 'param2' => 42))
	 *                 ->setModels(array($model1, $model2));
	 * echo $output->render();
	 * </code></pre>
	 *
	 * @return string CLI formatted output.
	 */
	public function render()
	{
		$title = 'Extracted data from "' . $this->getName() . '"';
		$output = $title . "\n" . str_repeat("=", strlen($title)) . "\n";
		$output .= "URL:\n" . $this->renderUrls($this->getUrl());
		
		$output .= "Input:\n";
		foreach ($this->getInputParameters() as $key => $value)
		{
			$output .= str_repeat(" ", 4) .  "- " . $key . " = " . $this->renderValue($value) . "\n";
		}
		$output .= "\n";
		
		foreach ($this->getModels() as $modelIndex => $model)
		{
			$modelTitle = 'Model ' . (++$modelIndex) . ':';
			$output .= $modelTitle . "\n" . str_repeat('-', strlen($modelTitle)) . "\n";
			
			if (!($model instanceof Snaphe_Model_Interface))
			{
				$output .= "Invalid model\n\n";
				continue;
			}
			foreach ($model->getValues() as $key => $value)
			{
				$output .= str_repeat(' ', 4) . '- ' . $key . ' = ';
				if (is_array($value))
				{
					$output .= $this->renderValue($value, 2);
				}
				else
				{
					$output .= $this->renderValue($value);
				}
				$output .= "\n";
			}
			
			$output .= "\n";
		}
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
			$return = '';
			foreach ($value as $valueKey => $valueValue)
			{
				$return .= $prefix . $valueKey . ' = ' . $this->renderValue($valueValue, is_array($valueValue) ? $level+1 : 0);
			}
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
				$result .= str_repeat(' ', 4) . '- ' . $url . "\n";
			}
			return $result;
		}
		return str_repeat(' ', 4) . $urls . "\n";
	}

}
