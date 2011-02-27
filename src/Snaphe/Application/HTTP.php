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
 * Snaphe HTTP application.
 *
 * @package com.snaphe.application
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Application_HTTP extends Snaphe_Application_Abstract
{
	
	/** @var string */
	protected $default_output_class = 'Snaphe_Output_HTML';
	
		/**
		 * Prints the usage information.
		 *
		 * @return integer  The return value of the command (0 if successful)
		 */
		protected function usage()
		{
			$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			echo <<<EOF
<html><head><title>Snaphe Usage Information</title></head><body>
<h1>HTTP utility for the Snaphe web extraction library.</h1>

<h2>Usage:</h2>
<p>Execute the wrapper:</p>
<pre><code>$url?wrapper=name[&format=(cli|xml|text|json|html|no)][&no-output=(0|1)][&file=...][&wrapper parameters...]</code></pre>

<h3>Options:</h3>
<ul>
	<li><code>--no-output</code> Surpress output.</li>
	<li><code>--format</code> Output format (cli|xml|text|json|html|no).</li>
	<li><code>--file</code> Output file.</li>
</ul
</body></html>
EOF;
			return 0;
		}

}