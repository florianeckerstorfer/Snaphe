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
 * Executes a search on Google and returns the results.
 *
 * Parameters:
 *  - "q" (required)
 *
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class GoogleSearchWrapper extends Snaphe_Wrapper_Abstract
{
	
	/** @var array */
	protected $models;
	
	/** @var array */
	protected $definedParameters = array('q');
	
	/** @var array */
	protected $requiredParameters = array('q');
	
	public function __construct(array $parameters)
	{
		$this->initializeParameters($parameters);
	}
	
	public function execute()
	{
		$client = new Snaphe_HTTP_Client();
		
		$this->urls[0] = 'http://www.google.com/search?q=' . urlencode($this->getParameter('q'));
		$page = new Snaphe_Page($this->urls[0]);
		$page->setCurlClient($client);
		
		$model = new Snaphe_Model_List();
		$titleRegExp = new Snaphe_Selector_RegExp('/<h3(.*)>(.*)<\/h3>/Us', 2);
		$titleRegExp->registerCallback('my_strip_tags');
		$descriptionRegExp = new Snaphe_Selector_RegExp('/<div class=\"s\">(.*)<br>/Us');
		$descriptionRegExp->registerCallback('my_strip_tags');

		$model->addValue('title', $titleRegExp);
		$model->addValue('description', $descriptionRegExp);

		$page->extract(array($model));
		$this->models[] = $model;
	}
	
	public function getModels()
	{
		return $this->models;
	}

}

function my_strip_tags($value)
{
	if (is_array($value))
	{
		for ($i = 0; $i < count($value); ++$i)
		{ 
			$value[$i] = strip_tags($value[$i]);
		}
		return $value;
	}
	return strip_tags($value);
}
