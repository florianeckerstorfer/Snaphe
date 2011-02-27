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
 * Abstract selector class. This class contains generic method which can be used by all or most concrete selectors.
 *
 * @package com.snaphe.selector
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
abstract class Snaphe_Selector_Abstract implements Snaphe_Selector_Interface
{
	
	/** @var array */
	protected $callbacks = array();
	
	/**
	 * Allows to register a callback function which is applied to every extracted value.
	 *
	 * @param callback $callback Callback function/method
	 * @return Snaphe_Selector_Abstract
	 */
	public function registerCallback($callback)
	{
		if (!is_callable($callback))
		{
			throw new InvalidArgumentException('$callback is not callable.');
		}
		$this->callbacks[] = $callback;
		return $this;
	}
	
	/**
	 * Applies all callbacks to the given value.
	 *
	 * @param mixed $value Value
	 * @return mixed Value with applied callbacks.
	 */
	protected function applyCallbacks($value)
	{
		foreach ($this->callbacks as $callback)
		{
			$value = call_user_func($callback, $value);
		}
		return $value;
	}
	
}
