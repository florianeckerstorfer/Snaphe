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
 * Wrapper for PHPs cURL library.
 *
 * @package com.snaphe.http
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_HTTP_Client
{
	
	/** @var array */
	protected $options = array();
	
	/** @var array */
	protected $proxyServers = array();
	
	/** @var array */
	protected $headers = array();
	
	/** @var array */
	protected $cookies = array();
	
	/**
	 * Sets the user agent.
	 *
	 * Example:
	 * 
	 * <code>
	 * $client = new Snaphe_HTTP_Client();
	 * $client->setUserAgent('My Wrapper 0.1');
	 * </code>
	 *
	 * @param string $userAgent User agent
	 * @return Snaphe_HTTP_Client
	 */
	public function setUserAgent($userAgent)
	{
		$this->options[CURLOPT_USERAGENT] = $userAgent;
		return $this;
	}
	
	/**
	 * Returns the user agent.
	 *
	 * @return string User agent
	 */
	public function getUserAgent()
	{
		if (!isset($this->options[CURLOPT_USERAGENT]))
		{
			return null;
		}
		return $this->options[CURLOPT_USERAGENT];
	}
	
	/**
	 * Sets the referer.
	 *
	 *
	 * Example:
	 * 
	 * <code>
	 * $client = new Snaphe_HTTP_Client();
	 * $client->setReferer('http://example.com');
	 * </code>
	 *
	 * @param string $referer Referer
	 * @return Snaphe_HTTP_Client
	 */
	public function setReferer($referer)
	{
		$this->options[CURLOPT_REFERER] = $referer;
		return $this;
	}
	
	/**
	 * Returns the referer.
	 *
	 * @return string Referer
	 */
	public function getReferer()
	{
		if (!isset($this->options[CURLOPT_REFERER]))
		{
			return null;
		}
		return $this->options[CURLOPT_REFERER];
	}
	
	/**
	 * Sets the encoding.
	 *
	 * Example:
	 * <pre><code>
	 * $client = new Snaphe_HTTP_Client();
	 * $client->setEncoding('gzip');
	 * </code></pre>
	 *
	 * @param string $encoding Encoding (identity|defalte|gzip)
	 * @return Snaphe_HTTP_Client
	 */
	public function setEncoding($encoding)
	{
		$this->options[CURLOPT_ENCODING] = $encoding;
		return $this;
	}
	
	/**
	 * Returns the encoding.
	 *
	 * @return string Encoding
	 */
	public function getEncoding()
	{
		return isset($this->options[CURLOPT_ENCODING]) ? $this->options[CURLOPT_ENCODING] : null;
	}
	
	/**
	 * Sets the HTTP headers.
	 *
	 * @param array $headers HTTP headers
	 * @return Snaphe_HTTP_Client
	 */
	public function setHeaders(array $headers)
	{
		$this->headers = $headers;
		return $this;
	}
	
	/**
	 * Adds a HTTP header to the request.
	 *
	 * Example:
	 * <pre><code>
	 * $client = new Snaphe_HTTP_Client();
	 * $client->addHeader('X-Custom-Header', '42');
	 * </code></pre>
	 *
	 * @param string $key Key
	 * @param string $value Value
	 * @return Snaphe_HTTP_Client
	 */
	public function addHeader($key, $value)
	{
		$this->headers[$key] = $value;
		return $this;
	}
	
	/**
	 * Returns the HTTP headers.
	 *
	 * @return array HTTP headers
	 */
	public function getHeaders()
	{
		return $this->headers;
	}
	
	/**
	 * Returns a HTTP header.
	 *
	 * @param string $key Key of the HTTP header.
	 * @return string Value of the HTTP header.
	 */
	public function getHeader($key)
	{
		return isset($this->headers[$key]) ? $this->headers[$key] : null;
	}
	
	/**
	 * Sets the cookies. All currently existing cookies will be removed.
	 * 
	 * This will include a "Set-cookie"-header to the HTTP request.
	 *
	 * Example:
	 * <pre><code>
	 * $client = new Snaphe_HTTP_Client();
	 * $client->setCookies(array(
	 *     'user_id' => 'u001349',
	 *     'my_cookie' => 'foobar'
	 * ));
	 * </code></pre>
	 *
	 * @param array $cookies Cookies
	 * @return Snaphe_HTTP_Client
	 */
	public function setCookies($cookies)
	{
		if (null == $cookies)
		{
			return $this;
		}
		$this->cookies = $cookies;
		return $this;
	}

	/**
	 * Adds a cookie.
	 *
	 * This will include a "Set-cookie"-header to the HTTP request.
	 *
	 * Example:
	 * <pre><code>
	 * $client = new Snaphe_HTTP_Client();
	 * $client->addCookie('user_id', 'u001349');
	 * </code></pre>
	 *
	 * @param string $key Cookie key
	 * @param string $value Cookie value
	 * @return Snaphe_HTTP_Client
	 */
	public function addCookie($key, $value)
	{
		$this->cookies[$key] = $value;
		return $this;
	}
	
	/**
	 * Returns the cookies.
	 *
	 * @return array Cookies
	 */
	public function getCookies()
	{
		return $this->cookies;
	}
	
	/**
	 * Returns a cookie.
	 *
	 * @param string $key Cookie key
	 * @return string Cookie value
	 */
	public function getCookie($key)
	{
		return isset($this->cookies[$key]) ? $this->cookies[$key] : null;
	}
	
	/**
	 * Adds a proxy server to the client.
	 *
	 * @param string $host Hostname
	 * @param integer $port Port
	 * @param string $name Name of the proxy
	 * @param string $username Username
	 * @param string $password Password
	 * @return Snaphe_HTTP_Client
	 */
	public function addProxyServer($host, $port, $name = null, $username = null, $password = null)
	{
		$this->proxyServers[] = array($host, $port, $name, $username, $password);
		return $this;
	}
	
	/**
	 * Returns the proxy server by name.
	 *
	 * @param string $name Name of the proxy
	 * @return array Array with Host, port and name of the proxy.
	 */
	public function getProxyServer($name)
	{
		foreach ($this->proxyServers as $proxy)
		{
			if ($proxy[2] == $name)
			{
				return $proxy;
			}
		}
		return null;
	}
	
	/**
	 * Returns a random proxy.
	 *
	 * @return array Array with Host, port and name of the proxy.
	 */
	public function getRandomProxyServer()
	{
		if (0 == count($this->proxyServers))
		{
			return null;
		}
		return $this->proxyServers[rand(0, count($this->proxyServers)-1)];
	}

	/**
	 * Executes a GET request
	 *
	 * Simple GET request:
	 * <pre><code>
	 * $client = new Snaphe_HTTP_Client();
	 * $response = $client->executeGet('http://echo.pckg.org', array(
	 *     'param1' => 'foobar',
	 *     'param2' => '42'
	 * ));
	 * echo $response->getBody();
	 * </code></pre>
	 *
	 * GET request through a proxy server
	 * <pre><code>
	 * $client = new Snaphe_HTTP_Client();
	 * $client->addProxyServer('192.168.0.0', 8080, 'myproxy', 'user', 'pass');
	 * $response = $client->executeGet('http://echo.pckg.org', array(), 'myproxy');
	 * echo $response->getBody();
	 * </code></pre>
	 *
	 * @param string $url URL
	 * @param array $getParameters GET parameters
	 * @return string Response text
	 */
	public function executeGet($url, array $getParameters = array(), $proxy = false)
	{
		return $this->execute($url, $getParameters, array(), $proxy);
	}
	
	/**
	 * Executes a POST request
	 *
	 * Simple POST request:
	 * <pre><code>
	 * $client = new Snaphe_HTTP_Client();
	 * $response = $client->executePost('http://echo.pckg.org', array(
	 *     'param1' => 'foobar',
	 *     'param2' => '42'
	 * ));
	 * echo $response->getBody();
	 * </code></pre>
	 *
	 * @param string $url URL
	 * @param array $postParameters Post parameters
	 * @return string Response text
	 */
	public function executePost($url, array $postParameters = array(), $proxy = false)
	{
		return $this->execute($url, array(), $postParameters, $proxy);
	}
	
	/**
	 * Executes a cURL request.
	 *
	 * @param string $url URL
	 * @param array $getParameters GET parameters
	 * @param array $postParameters POST parameters
	 * @return Snaphe_HTTP_Response Response object
	 */
	protected function execute($url, array $getParameters = array(), array $postParameters = array(), $proxy = false)
	{
		// Handle GET parameters
		$url .= count($getParameters) > 0 ? '?' . http_build_query($getParameters) : '';
		
		// Initialize cURL
		$curl = curl_init();
		$this->options[CURLOPT_RETURNTRANSFER] = true;
		$this->options[CURLOPT_URL] = $url;
		$this->options[CURLOPT_HEADER] = true;
		
		// Configure proxy server if one should be used.
		if (false !== $proxy)
		{
			if (true === $proxy)
			{
				$proxy = $this->getRandomProxyServer();
			}
			else
			{
				$proxy = $this->getProxyServer($proxy);
			}
			if (!is_null($proxy))
			{
				$this->options[CURLOPT_PROXY] = 'http://' . $proxy[0] . ':' . $proxy[1];
				$this->options[CURLOPT_PROXYPORT] = $proxy[1];
				// If available add username and password for the proxy.
				if (isset($proxy[3]) && isset($proxy[4]) && !is_null($proxy[3]) && !is_null($proxy[4]))
				{
					$this->options[CURLOPT_PROXYUSERPWD] = $proxy[3] . ':' . $proxy[4];
				}
			}
		}
		
		
		// Handle POST parameters
		if (count($postParameters) > 0)
		{
			$this->options[CURLOPT_POST] = 1;
			$this->options[CURLOPT_POSTFIELDS] = http_build_query($postParameters);
		}
		
		// Handle HTTP headers
//		$this->headers['Expect'] = '';
		if (count($this->headers))
		{
			$this->options[CURLOPT_HTTPHEADER] = array();
			foreach ($this->headers as $key => $value)
			{
				$this->options[CURLOPT_HTTPHEADER][] = $key . ": " . $value;
			}
		}
		
		// Handle cookies
		if (count($this->cookies))
		{
			$cookies_temp = array();
			foreach ($this->cookies as $key => $value)
			{
				$cookies_temp[] = $key . '=' . $value;
			}
			$this->options[CURLOPT_COOKIE] = implode('; ', $cookies_temp);
		}
		
		// Set options to cURL handle
		curl_setopt_array($curl, $this->options);
		
		// Execute request. get info and close cURL handle.
		$response = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		
		$this->options[CURLOPT_POST] = 0;
		$this->options[CURLOPT_POSTFIELDS] = '';
		
		// Create response object
		return new Snaphe_HTTP_Response($response, $info);
	}
	
}
