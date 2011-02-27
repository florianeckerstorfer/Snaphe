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
 * XPath selector class.
 *
 * @package com.snaphe.selector
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Selector_XPath extends Snaphe_Selector_Abstract
{
	
	/** @var string */
	protected $xpath;
	
	/**
	 * Constructor.
	 *
	 * Example:
	 * <pre><code>
	 * $selector = new Snaphe_Selector_XPath('//strong');
	 * $result = $selector->extract('&lt;p&gt;&lt;strong&gt;Hello World&lt;/strong&gt;! The answer is &lt;strong&gt;42&lt;/strong&gt;.&lt;/p&gt;');
	 * </code></pre>
	 *
	 * @param string $xpath XPath
	 */
	public function __construct($xpath)
	{
		$this->xpath = $xpath;
	}

	/**
	 * Extracts the value from the given input.
	 *
	 * @param string $input Input
	 * @return string|array|false Extracted value or FALSE if no value could be extracted.
	 */
	public function extract($input)
	{
		$input = utf8_encode($input);
		$document = new DOMDocument();
		$document->loadHTML($input);
		$xml = $document->saveXML();
		$xml = simplexml_load_string($input);
		if (!$xml)
		{
			return null;
		}
		$result = $xml->xpath($this->xpath);
		if (false === $result || 0 == count($result))
		{
			return null;
		}
		elseif (is_array($result) && 1 == count($result))
		{
			if (count($result[0]->children()) > 1)
			{
				return $this->applyCallbacks($result[0]->asXML());
			}
			return $this->applyCallbacks(strval($result[0]));
		}
		elseif (is_array($result))
		{
			for ($i = 0; $i < count($result); ++$i)
			{
				if (count($result[$i]->children()) > 1)
				{
					$result[$i] = $result[$i]->asXML();
				}
				else
				{
					$result[$i] = strval($result[$i]);
				}
			}
		}
		return $this->applyCallbacks($result);
	}
	
}
