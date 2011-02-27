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
 * List model class.
 *
 * @package com.snaphe.model
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Model_List extends Snaphe_Model_Abstract
{
	
	/**
	 * Extracts the selectors from the given input.
	 *
	 * @param string $input Input
	 * @return boolean TRUE if all selectors succeed, FALSE if one or more fail.
	 */
	public function extract($input)
	{
		$result = true;
		// Iterate through all selectors (transform array [x][y] to [y][x])
		foreach ($this->selectors as $valueName => $selector)
		{
			// Extract values
			$values = $selector->extract($input);
			// Check if extraction returned values
			if (false == $values || is_null($values))
			{
				$result = false;
				continue;
			}
			if (!is_array($values))
			{
				$values = array($values);
			}
			// Iterate through all values
			for ($i = 0; $i < count($values); ++$i)
			{ 
				if (!isset($this->values[$i]))
				{
					$this->values[$i] = array();
				}
				$this->values[$i][$valueName] = $values[$i];
				if (false === $this->values[$i][$valueName] || is_null($this->values[$i][$valueName]))
				{
					$result = false;
				}
			}
		}
		return $result;
	}
	
}
