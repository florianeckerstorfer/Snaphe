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
 * Extracts information from a Wikipedia page using RegExp selectors.
 *
 * Parameters:
 *  - "article" (required)
 *
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class WikipediaArtistArticleWrapper extends Snaphe_Wrapper_Abstract
{
	
	/** @var Snaphe_Page */
	protected $page;
	
	/** @var array */
	protected $models;
	
	/** @var array */
	protected $definedParameters = array('article');
	
	/** @var array */
	protected $requiredParameters = array('article');
	
	public function __construct(array $parameters)
	{
		$this->initializeParameters($parameters);
	}
	
	public function execute()
	{
		$client = new Snaphe_HTTP_Client();
		
		$this->urls[0] = 'http://en.wikipedia.org/wiki/' . urlencode($this->getParameter('article'));
		$page = new Snaphe_Page($this->urls[0]);
		$page->setCurlClient($client);
		
		$model = new Snaphe_Model();
		$originRegExp = new Snaphe_Selector_RegExp('/<th>Origin<\/th>\n<td>(.*)<\/td>/');
		$originRegExp->registerCallback('strip_tags');
		$genresRegExp = new Snaphe_Selector_RegExp('/<th(.*)><a(.*)>Genres<\/a><\/th>\n<td>(.*)<\/td>/U', 3);
		$genresRegExp->registerCallback('strip_tags');
		$yearsActiveRegExp = new Snaphe_Selector_RegExp('/<th(.*)>Years active<\/th>\n<td>(.*)<\/td>/U', 2);
		$yearsActiveRegExp->registerCallback('strip_tags');
		$labelsRegExp = new Snaphe_Selector_RegExp('/<th(.*)><a(.*)>Labels<\/a><\/th>\n<td>(.*)<\/td>/U', 3);
		$labelsRegExp->registerCallback('strip_tags');
		$associatedActsRegExp = new Snaphe_Selector_RegExp('/<th(.*)>Associated acts<\/th>\n<td>(.*)<\/td>/U', 2);
		$associatedActsRegExp->registerCallback('strip_tags');
		$websiteRegExp = new Snaphe_Selector_RegExp('/<th>Website<\/th>\n<td>(.*)<\/td>/');
		$websiteRegExp->registerCallback('strip_tags');
		$model->addValue('name', new Snaphe_Selector_RegExp('/<h1 id=\"firstHeading\" class=\"firstHeading\">(.*)<\/h1>/'))
			  ->addValue('origin', $originRegExp)
			  ->addValue('genres', $genresRegExp)
			  ->addValue('yearsActive', $yearsActiveRegExp)
			  ->addValue('labels', $labelsRegExp)
			  ->addValue('associatedActs', $associatedActsRegExp)
			  ->addValue('website', $websiteRegExp);

		$page->extract(array($model));
		$this->models[] = $model;
	}
	
	public function getModels()
	{
		return $this->models;
	}

}
