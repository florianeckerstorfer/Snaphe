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
 * CSS selector class.
 *
 * @package com.snaphe.selector
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Selector_CSS extends Snaphe_Selector_Abstract
{
	
	/** @var string */
	protected $selector;
	
	/**
	 * Constructor
	 *
	 * Example:
	 * <pre><code>
	 * $selector = new Snaphe_Selector_CSS('strong.foobar');
	 * $result = $selector->extract('&lt;p&gt;&lt;strong class=&quot;foo&quot;&gt;Hello World&lt;/strong&gt;! The answer is &lt;strong&gt;42&lt;/strong&gt;.&lt;/p&gt;');
	 * </code></pre>
	 *
	 * @param string $selector CSS selector
	 */
	public function __construct($selector)
	{
		$this->selector = $selector;
	}
	
	/**
	 * Applies the selector to the given input text.
	 *
	 * @param string $input Input text.
	 * @return array Extracted values (NULL if no values could be extracted).
	 */
	public function extract($input)
	{
		$selector = new Joy_DOM_CSSSelector($input);
		
		// Apply the selector to the DOM
		$result = $selector->select($this->selector, false);
		
		// The selector extracted exactly one value.
		if ($result instanceof DOMNodeList && 1 == $result->length)
		{
			// The single extracted value contains child elements
			if (count($selector->elements_to_array($result->item(0)->childNodes)) > 0)
			{
				return $this->applyCallbacks($this->domNodeList_to_string($result));
			}
			// The single extracted value does not contain child elements
			return $this->applyCallbacks($result->item(0)->textContent);
		}
		
		// The selector extracted more than one value.
		elseif ($result instanceof DOMNodeList && $result->length > 1)
		{
			$return = array();
			for ($i = 0; $i < $result->length; ++$i)
			{ 
				// The value contains child elements
				if (count($selector->elements_to_array($result->item($i)->childNodes)) > 0)
				{
					$return[$i] = $this->domNodeList_to_string($result->item($i));
				}
				// The value does not contain child elements
				else
				{
					$return[$i] = $result->item($i)->textContent;
				}
			}
			return $this->applyCallbacks($return);
		}
		
		// Return NULL if no values could be extracted
		return null;
	}
	
	/**
	 * Converts a DOMNodeList object into a string.
	 *
	 * @param DOMNodeList $DomNodeList 
	 * @return string XML code of the given DOMNodeList object.
	 */
	public function domNodeList_to_string(DOMNodeList $DomNodeList) { 
		$output = ''; 
		$doc = new DOMDocument; 
		$i = 0;
		// First convert DOMNodelist into DOMDocument
		while ($node = $DomNodeList->item($i))
		{ 
			// import node 
			$domNode = $doc->importNode($node, true); 
			// append node 
			$doc->appendChild($domNode); 
			$i++; 
		}
		// Convert DOMDocument into XML
		$output = $doc->saveXML(); 
		$output = print_r($output, 1);
		$output = trim(str_replace('<?xml version="1.0"?>', '', $output));
		return $output; 
	}

}
