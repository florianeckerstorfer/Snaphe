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
 * Abstract output class with common methods for rendering extracted data.
 *
 * @package com.snaphe.output
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
abstract class Snaphe_Output_Abstract implements Snaphe_Output_Interface
{
	
	/** @var string */
	protected $name;
	
	/** @var string */
	protected $url;
	
	/** @var array */
	protected $inputParameters;
	
	/** @var array */
	protected $models;
	
	/**
	 * Sets the name of the wrapper.
	 *
	 * @param string $name Wrapper name
	 * @return Snaphe_Output_Abstract
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Returns the name of the wrapper.
	 *
	 * @return string Name of the wrapper.
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Sets the URL
	 *
	 * @param string $url URL
	 * @return Snaphe_Output_Abstract
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}
	
	/**
	 * Returns the URL.
	 *
	 * @return string URL
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * Sets the input parameters.
	 *
	 * @param array $inputParameters Input parameters
	 * @return Snaphe_Output_Abstract
	 */
	public function setInputParameters(array $inputParameters)
	{
		$this->inputParameters = $inputParameters;
		return $this;
	}
	
	/**
	 * Returns the input parameters.
	 *
	 * @return array Input parameters
	 */
	public function getInputParameters()
	{
		return $this->inputParameters;
	}

	/**
	 * Sets the models.
	 *
	 * @param array $models Array with models.
	 * @return Snaphe_Output_Abstract
	 */
	public function setModels(array $models)
	{
		$this->models = $models;
		return $this;
	}
	
	/**
	 * Returns an array with models
	 *
	 * @return array Array with models.
	 * @author Florian Eckerstorfer
	 */
	public function getModels()
	{
		return $this->models;
	}
	
}
