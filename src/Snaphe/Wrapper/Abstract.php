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
 * Abstract class which implements common methods for wrappers.
 *
 * @package com.snaphe.wrapper
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
abstract class Snaphe_Wrapper_Abstract implements Snaphe_Wrapper_Interface
{
	
	/** @var array */
	protected $definedParameters = array();
	
	/** @var array */
	protected $requiredParameters = array();
	
	/** @var array */
	protected $parameters = array();
	
	/** @var array */
	protected $urls;
	
	/** @var Snaphe_HTTP_Client */
	protected $defaultClient;
	
	/** @var array */
	protected $models = array();
	
	/**
	 * Returns the visited URLs.
	 *
	 * @return array Array with visited URLs.
	 */
	public function getUrls()
	{
		return $this->urls;
	}
	
	/**
	 * Returns the data models.
	 *
	 * @return array Array with {@see Snaphe_Model_Interface} objects.
	 */
	public function getModels()
	{
		return $this->models;
	}
	
	/**
	 * Sets the default client object.
	 * 
	 * @param Snaphe_HTTP_Client $defaultClient Default client
	 * @return Snaphe_Wrapper_Abstract
	 */
	public function setDefaultClient(Snaphe_HTTP_Client $defaultClient)
	{
		$this->defaultClient = $defaultClient;
		return $this;
	}

	/**
	 * Initalizes the parameters
	 *
	 * @param array $parameters 
	 * @return Snaphe_Wrapper_Abstract
	 */
	protected function initializeParameters(array $parameters)
	{
		// set parameters
		foreach ($this->definedParameters as $key)
		{
			if (isset($parameters[$key]) && null !== $parameters[$key])
			{
				$this->parameters[$key] = $parameters[$key];
			}
		}
		
		// check if all required parameters are definied.
		foreach ($this->requiredParameters as $key)
		{
			if (!$this->hasParameter($key))
			{
				throw new Snaphe_Exception('Parameter "' . $key . '" is required for this wrapper.');
			}
		}
		
		return $this;
	}
	
	/**
	 * Sets the given parameter.
	 *
	 * @param string $key Parameter key
	 * @param mixed $value Parameter value
	 * @return Snaphe_Wrapper_Abstract
	 * @throws Snaphe_Exception if the parameter is not available.
	 */
	public function setParameter($key, $value)
	{
		if (!in_array($key, $this->definedParameters))
		{
			throw new Snaphe_Exception('Parameter "' . $key . '" is not available in this wrapper.');
		}
		$this->parameters[$key] = $value;
		return $this;
	}
	
	/**
	 * Returns the value of the given parameter.
	 *
	 * @param string $key Parameter key
	 * @return mixed Parameter value
	 */
	public function getParameter($key)
	{
		if (!isset($this->parameters[$key]))
		{
			return null;
		}
		return $this->parameters[$key];
	}
	
	/**
	 * Returns if the given parameter exists.
	 *
	 * @param string $key Parameter key
	 * @return boolean TRUE if the parameter exists, FALSE if not.
	 * @author Florian Eckerstorfer
	 */
	public function hasParameter($key)
	{
		return isset($this->parameters[$key]);
	}
	
	/**
	 * Adds a parameter to the list of defined parameters.
	 *
	 * @param string $key Parameter key
	 * @param boolean $required TRUE if the parameter is required, FALSE if not.
	 * @return Snaphe_Wrapper_Abstract
	 */
	protected function defineParameter($key, $required = false)
	{
		$this->definedParameters[] = $key;
		if ($required)
		{
			$this->requiredParameters[] = $key;
		}
		return $this;
	}
	
	/**
	 * Creates a new page object and returns ist.	
	 *
	 * @param string $url URL
	 * @param Snaphe_HTTP_Client $client Client object or NULL if default client should be used.
	 * @return Snaphe_Page
	 */
	protected function getPage($url, array $parameters = array(), $post = false, $proxy = false, $client = null)
	{
		if (null != $client && !($client instanceof Snaphe_HTTP_Client))
		{
			throw new Snaphe_Exception('$client must be an instance of Snaphe_HTTP_Client or null.');
		}
		if (null == $client && null == $this->defaultClient)
		{
			throw new Snaphe_Exception('No client given and no default client specified.');
		}
		$this->urls[] = $url;
		$page = new Snaphe_Page($url, $parameters, $post, $proxy);
		$page->setCurlClient((null == $client ? $this->defaultClient : $client));
		return $page;
	}
	
}
