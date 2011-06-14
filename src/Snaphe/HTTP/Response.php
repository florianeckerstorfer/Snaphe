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
 * HTTP response class.
 *
 * @package com.snaphe.http
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_HTTP_Response
{
	
	/** @var array */
	protected $infos;
	
	/** @var array */
	protected $headers;
	
	/** @var string */
	protected $body;
	
	/** @var array */
	protected $cookies;
	
	/**
	 * Constructor.
	 *
	 * @param string $responseText Response (contains header and body separated by "\r\n\r\n").
	 * @param array $infos Infos
	 */
	public function __construct($responseText, array $infos = array())
	{
		$this->infos = $infos;
		
		$lines = explode("\n", $responseText);
		$count = count($lines);
		for ($i = 0; $i < $count; ++$i)
		{
			if (preg_match('/^HTTP\//', $lines[$i]))
			{
				unset($lines[$i]);
			}
			elseif (preg_match("/^(<|{)/", $lines[$i]))
			{
				break;
			}
			else//if (preg_match("/^(.*):(\s*)(.*)/", $lines[$i]))
			{
				$key = trim(substr($lines[$i], 0, strpos($lines[$i], ':')));
				$value = trim(substr($lines[$i], strpos($lines[$i], ':')+1));
				if ('Set-Cookie' == $key)
				{
					$cookies = explode(';', $value);
					foreach ($cookies as $cookie)
					{
						$cookie_key = trim(substr($cookie, 0, strpos($cookie, '=')));
						$cookie_value = trim(substr($cookie, strpos($cookie, '=')+1));
						$this->cookies[$cookie_key] = $cookie_value;
					}
				}
				else
				{
					$this->headers[$key] = $value;
				}
				unset($lines[$i]);
			}
		}
		$this->body = trim(implode("\n", $lines));
	}
	
	/**
	 * Returns information about the response.
	 *
	 * @param string $name Name of the info.
	 * @return mixed Value of the info.
	 */
	public function getInfo($name)
	{
		if (!isset($this->infos[$name]))
		{
			return null;
		}
		return $this->infos[$name];
	}
	
	/**
	 * Returns the given header.
	 *
	 * @param string $name Name of the header
	 * @return string Value of the header.
	 */
	public function getHeader($name)
	{
		if (!isset($this->headers[$name]))
		{
			return null;
		}
		return $this->headers[$name];
	}
	
	/**
	 * Returns the body of the response
	 *
	 * @return string Body of the response
	 */
	public function getBody()
	{
		return $this->body;
	}
	
	/**
	 * Returns the cookie with the given name.
	 *
	 * @param string $name Name of the cookie
	 * @return string Value of the cookie.
	 */
	public function getCookie($name)
	{
		if (!isset($this->cookies[$name]))
		{
			return null;
		}
		return $this->cookies[$name];
	}
	
	/**
	 * Returns all cookies.
	 *
	 * @return array Array with all cookies.
	 */
	public function getCookies()
	{
		return $this->cookies;
	}

}
