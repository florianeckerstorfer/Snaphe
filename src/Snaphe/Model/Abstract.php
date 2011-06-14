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
 * Abstract model class.
 *
 * @package com.snaphe.model
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
abstract class Snaphe_Model_Abstract implements Snaphe_Model_Interface
{
	
	/** @var array */
	protected $selectors = array();
	
	/** @var array */
	protected $values = array();
	
	/**
	 * Adds a value and its selector to the model.
	 *
	 * @param string $name Name of the value
	 * @param Snaphe_Selector_Interface $selector Selector for the model
	 * @return Snaphe_Model
	 */
	public function addValue($name, Snaphe_Selector_Interface $selector)
	{
		$this->selectors[$name] = $selector;
		return $this;
	}
	
	/**
	 * Returns the selector for the given value.
	 *
	 * @param string $name Name of the value.
	 * @return Snaphe_Selector_Interface Selector for the given value.
	 */
	public function getSelector($name)
	{
		return isset($this->selectors[$name]) ? $this->selectors[$name] : null;
	}
	
	/**
	 * Sets the values to the given array.
	 *
	 * @param array $values array with values.
	 * @return Snaphe_Model_Abstract
	 */
	public function setValues(array $values)
	{
		$this->values = $values;
		return $this;
	}
	
	/**
	 * Returns all extracted values.
	 *
	 * @return array Extracted values
	 */
	public function getValues()
	{
		return $this->values;
	}
	
	/**
	 * Sets the value of a value.
	 * 
	 * @param string $name Name
	 * @param string $value Value
	 */
	public function setValue($name, $value)
	{
		$this->values[$name] = $value;
		return $this;
	}
	
	/**
	 * Returns the given value
	 *
	 * @param string $name Name of the value
	 * @return mixed Value
	 */
	public function getValue($name)
	{
		if (!isset($this->values[$name]))
		{
			return null;
		}
		return $this->values[$name];
	}
	
}
