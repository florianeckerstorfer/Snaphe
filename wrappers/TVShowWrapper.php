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
 * Extracts information about a TV show from TVRage using XPath selectors.
 *
 * Parameters:
 *  - "show" (required; Name of a TV show)
 *
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class TVShowWrapper extends Snaphe_Wrapper_Abstract
{
	
	/** @var Snaphe_HTTP_Client */
	protected $client;
	
	/** @var array */
	protected $models;
	
	/** @var array */
	protected $definedParameters = array('show');
	
	/** @var array */
	protected $requiredParameters = array('show');
	
	public function __construct(array $parameters)
	{
		$this->initializeParameters($parameters);
	}
	
	public function execute()
	{
		$client = new Snaphe_HTTP_Client();
		
		$this->urls[0] = 'http://services.tvrage.com/feeds/search.php?show=' . urlencode($this->getParameter('show'));
		$page = new Snaphe_Page($this->urls[0]);
		$page->setCurlClient($client);
		$model = new Snaphe_Model_List();
		$model->addValue('sid', new Snaphe_Selector_XPath('//showid'));
		$page->extract(array($model));
		$values = $model->getValues();
		
		$this->urls[1] = 'http://services.tvrage.com/feeds/showinfo.php?sid=' . $values[0]['sid'];
		$page = new Snaphe_Page($this->urls[1]);
		$page->setCurlClient($client);
		$model = new Snaphe_Model();
		$model->addValue('name', new Snaphe_Selector_XPath('//showname'))
			  ->addValue('seasons', new Snaphe_Selector_XPath('//seasons'))
			  ->addValue('status', new Snaphe_Selector_XPath('//status'))
			  ->addValue('genres', new Snaphe_Selector_XPath('//genres/genre'));
		$page->extract(array($model));
		$this->models[] = $model;
	}
	
	public function getModels()
	{
		return $this->models;
	}

}
