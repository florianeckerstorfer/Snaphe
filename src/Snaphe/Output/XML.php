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
 * Generates output in XML.
 *
 * @package com.snaphe.output
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Output_XML extends Snaphe_Output_Abstract
{
	
	/**
	 * Renders the output class in XML format.
	 *
	 * Example:
	 * <pre><code>
	 * $output = new Snaphe_Output_XML();
	 * $output->setName('TestWrapper')
	 *                 ->setUrl('http://example.com')
	 *                 ->setInputParameters(array('param1' => 'foobar', 'param2' => 42))
	 *                 ->setModels(array($model1, $model2));
	 * echo $output->render();
	 * </code></pre>
	 *
	 * @return string XML formatted output.
	 */
	public function render()
	{
		$writer = new XMLWriter();
		$writer->openMemory();
		$writer->startDocument('1.0', 'utf-8', 'yes');
		$writer->setIndent(4);
		
		$writer->startElement('wrapper');
			$writer->writeElement('name', $this->getName());
			$urls = is_array($this->getUrl()) ? $this->getUrl() : array($this->getUrl());
			foreach ($urls as $url)
			{
				$writer->writeElement('url', $url);
			}
			foreach ($this->getInputParameters() as $key => $value)
			{
				$writer->startElement('input');
					$writer->writeElement('key', $key);
					$writer->writeElement('value', $value);
				$writer->endElement();
			}
			foreach ($this->getModels() as $modelIndex => $model)
			{
				$writer->startElement('model');
					foreach ($model->getValues() as $key => $value)
					{
						$writer->startElement('data');
							$writer->writeElement('key', $key);
							if (is_array($value))
							{
								foreach ($value as $valueKey => $valueValue)
								{
									$writer->startElement('data');
										$writer->writeElement('key', $valueKey);
										$writer->writeElement('value', $valueValue);
									$writer->endElement();
								}
							}
							else
							{
								$writer->writeElement('value', $value);
							}
						$writer->endElement();
					}
				$writer->endElement();
			}
		$writer->endElement();
		
		$writer->endDocument();
		return $writer->outputMemory(true);
	}

}
