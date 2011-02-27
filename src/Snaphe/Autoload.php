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
 * Autoload for Snaphe classes.
 *
 * @package com.snaphe.autoload
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Autoload
{

	/**
	 * Autoload method for Snaphe.
	 *
	 * @param string $class_name Name of the class to load.
	 */
	public static function autoload($class_name)
	{
		if ('Snaphe' === substr($class_name, 0, 6))
		{
			require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR
				. str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
		}
	}
	
	/**
	 * Registers the autoload class.
	 *
	 * Example:
	 * <pre><code>
	 * require_once dirname(__FILE__).'/../src/Snaphe/Autoload.php';
	 * Snaphe_Autoload::register();
	 * </pre></code>
	 *
	 * @return void
	 */
	public static function register()
	{
		spl_autoload_register(array('Snaphe_Autoload', 'autoload'));
	}

}
