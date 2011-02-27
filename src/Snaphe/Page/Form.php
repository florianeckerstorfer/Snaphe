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
 * Represents a form.
 *
 * @package com.snaphe.page
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Page_Form
{
	
	/** @var string */
	protected $action;
	
	/** @var string */
	protected $method;
	
	/** @var array */
	protected $fields;

	/**
	 * Constructor.
	 *
	 * @param string $action Form action
	 * @param string $method Form method
	 * @param array $fields Form fields
	 */
	public function __construct($action, $method, $fields)
	{
		$this->action = $action;
		$this->method = $method;
		$this->fields = $fields;
	}
	
	/**
	 * Returns the action of the form
	 *
	 * @return string Form action
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * Returns the method of the form.
	 *
	 * @return string Form method.
	 */
	public function getMethod()
	{
		return $this->method;
	}
	
	/**
	 * Returns the fields of the form.
	 *
	 * @return array Array with all fields of the form.
	 */
	public function getFields()
	{
		return $this->fields;
	}
	
	/**
	 * Returns an array with the names of all fields.
	 *
	 * @return array Array with the names of all fields.
	 */
	public function getFieldNames()
	{
		$names = array();
		foreach ($this->fields as $field)
		{
			$names[] = isset($field['name']) ? $field['name'] : null;
		}
		return $names;
	}

	/**
	 * Returns the values of the form.
	 *
	 * @return array Values of the form.
	 */
	public function getValues()
	{
		$values = array();
		foreach ($this->fields as $field)
		{
			if (isset($field['name']) && $field['name'])
			{
				$values[$field['name']] = isset($field['value']) ? $field['value'] : null;
			}
		}
		return $values;
	}
	
	/**
	 * Returns the given field.
	 *
	 * @param string $name Name of the field.
	 * @return array All information about the field.
	 */
	public function getField($name)
	{
		foreach ($this->fields as $field)
		{
			if (isset($field['name']) && $field['name'] == $name)
			{
				return $field;
			}
		}
		return null;
	}
	
	/**
	 * Returns the value of the given field.
	 *
	 * @param string $name Name of the field
	 * @return string Value of the field.
	 */
	public function getValue($name)
	{
		foreach ($this->fields as $field)
		{
			if (isset($field['name']) && $field['name'] == $name)
			{
				return $field['value'];
			}
		}
	}
	
	/**
	 * Returns the type of the given field.
	 *
	 * @param string $name Name of the field.
	 * @return string Type of the field.
	 */
	public function getType($name)
	{
		foreach ($this->fields as $field)
		{
			if (isset($field['name']) && $field['name'] == $name)
			{
				return $field['type'];
			}
		}
	}
	
	/**
	 * Returns all options of the given field.
	 *
	 * @param string $name Name of the field
	 * @return array Array with all options of the given field.
	 */
	public function getOptions($name)
	{
		foreach ($this->fields as $field)
		{
			if (isset($field['name']) && isset($field['options']) && $field['name'] == $name)
			{
				return $field['options'];
			}
		}
		return null;
	}

}
