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
 * Regular expression selector class.
 *
 * @package com.snaphe.selector
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Selector_RegExp extends Snaphe_Selector_Abstract
{
	
	/** @var string */
	protected $regexp;
	
	/** @var integer */
	protected $match;
	
	/**
	 * Constructor.
	 *
	 * Example:
	 * <pre><code>
	 * $selector = new Snaphe_Selector_RegExp('/&lt;h3&gt;(.*)&lt;\/h3&gt;/Us')
	 * $result = $selector->extract('&lt;div&gt;&lt;h3&gt;Hello World!&lt;/h3&gt;&lt;p&gt;Lorem ipsum...&lt;/p&gt;&lt;/div&gt;');
	 * </code></pre>
	 *
	 * Example:
	 * <pre><code>
	 * // This will select the second pattern (not the attributes of h3 tags, but the inner HTML).
	 * $selector = new Snaphe_Selector_RegExp('/<h3(.*)>(.*)<\/h3>/Us', 2);
	 * $result = $selector->extract('&lt;div&gt;&lt;h3 class=&quot;body&quot;&gt;Hello World!&lt;/h3&gt;&lt;p&gt;Lorem ipsum...&lt;/p&gt;&lt;/div&gt;')
	 * </code></pre>
	 *
	 * @param string $regexp Regular expression
	 * @param integer $match  ID of match that should be selected. Defaults to 0.
	 */
	public function __construct($regexp, $match = 1)
	{
		$this->regexp = $regexp;
		$this->match  = $match;
	}
	
	/**
	 * Selects the value from the given input.
	 *
	 * @param string $input Input
	 * @return string|array Selected value or NULL if the value could not be selected.
	 */
	public function extract($input)
	{
		if (!preg_match_all($this->regexp, $input, $matches))
		{
			return null;
		}
		elseif (is_array($this->match)) // if the $match argument in the constructor is an array
		{
			$result = array();
			$maxValues = 0;
			for ($i = 0; $i < count($this->match); ++$i) // iterate through all matches
			{
				$result[$i] = null;
				if (isset($matches[$this->match[$i]]) && is_array($matches[$this->match[$i]])
					&& 1 == count($matches[$this->match[$i]]) && isset($matches[$this->match[$i]][0]))
				{
					$result[$i] = array($this->applyCallbacks($matches[$this->match[$i]][0]));
				}
				elseif (isset($matches[$this->match[$i]]) && count($matches[$this->match[$i]]) > 0)
				{
					$result[$i] = $this->applyCallbacks($matches[$this->match[$i]]);
				}
				if (is_array($result[$i]))
				{
					$maxValues = max($maxValues, count($result[$i]));
				}
			}
			
			// We need to reorder the values [x][y] => [y][x]
			$newResult = array();
			for ($i = 0; $i < $maxValues; ++$i)
			{
				for ($j = 0; $j < count($result); ++$j)
				{
					if (isset($result[$j][$i]))
					{
						$newResult[$i][$j] = $result[$j][$i];						
					}
				}
			}
			
			return $newResult;
		}
		elseif (isset($matches[$this->match]) && is_array($matches[$this->match])
				&& 1 == count($matches[$this->match]) && isset($matches[$this->match][0]))
		{
			return $this->applyCallbacks($matches[$this->match][0]);
		}
		elseif (isset($matches[$this->match]) && count($matches[$this->match]) > 0)
		{
			return $this->applyCallbacks($matches[$this->match]);
		}
		return null;
	}
	
}
