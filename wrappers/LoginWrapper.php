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
 * Wrapper performs a simple login and extracts data from a secret page.
 *
 * Parameters:
 *  - username (required; try "user")
 *  - password (required; try "pass")
 *
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class LoginWrapper extends Snaphe_Wrapper_Abstract
{
	
	/** @var array */
	protected $models;
	
	/** @var array */
	protected $definedParameters = array('username', 'password');
	
	/** @var array */
	protected $requiredParameters = array('username', 'password');
	
	public function __construct(array $parameters)
	{
		$this->initializeParameters($parameters);
	}
	
	public function execute()
	{
		$this->client = new Snaphe_HTTP_Client();

		$this->urls[0] = 'http://echo.pckg.org/login.php';
		$r = $this->client->executePost($this->urls[0], array(
			'login' => 1,
			'username' => $this->getParameter('username'),
			'password' => $this->getParameter('password'),
		));
		$this->client->setCookies($r->getCookies());

		$this->urls[1] = 'http://echo.pckg.org/secret.php';
		$page = new Snaphe_Page($this->urls[1]);
		$page->setCurlClient($this->client);
		$model = new Snaphe_Model();
		$model->addValue('loginuser', new Snaphe_Selector_XPath('//strong[@class="loginuser"]'))
			  ->addValue('logindate', new Snaphe_Selector_XPath('//strong[@class="logindate"]'))
			  ->addValue('secret', new Snaphe_Selector_XPath('//strong[@class="secret"]'));
		$page->extract(array($model));
		$this->models[] = $model;
	}
	
	public function getModels()
	{
		return $this->models;
	}

}
