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
 * Snaphe CLI application.
 *
 * @package com.snaphe.application
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Application_Cli extends Snaphe_Application_Abstract
{
	
	/** @var string */
	protected $default_output_class = 'Snaphe_Output_Cli';
	
	/**
	 * Runs the Cli application.
	 *
	 * @param array $args Arguments (CLI format)
	 * @return integer Exit code.
	 */
	public function run(array $arguments)
	{
		$arguments = $this->parseArguments($arguments);
		return parent::run($arguments);
	}
	
	/**
	 * Parses the given $argv array into a standard associative array.
	 *
	 * @param array $argv Arguments (CLI format)
	 * @return array Arguments (Standard format)
	 */
	protected function parseArguments($argv)
	{
		array_shift($argv);
		$out = array();
		foreach ($argv as $arg)
		{
			if (substr($arg,0,2) == '--')
			{
				$eqPos = strpos($arg,'=');
				if ($eqPos === false)
				{
					$key = substr($arg,2);
					$out[$key] = isset($out[$key]) ? $out[$key] : true;
				}
				else
				{
					$key = substr($arg,2,$eqPos-2);
					$out[$key] = substr($arg,$eqPos+1);
				}
			}
			else if (substr($arg,0,1) == '-')
			{
				if (substr($arg,2,1) == '=')
				{
					$key = substr($arg,1,1);
					$out[$key] = substr($arg,3);
				}
				else
				{
					$chars = str_split(substr($arg,1));
					foreach ($chars as $char)
					{
						$key = $char;
						$out[$key] = isset($out[$key]) ? $out[$key] : true;
					}
				}
			}
			else
			{
				$out[] = $arg;
			}
		}
		return $out;
	}
	
	/**
	 * Prints the usage information.
	 *
	 * @return integer  The return value of the command (0 if successful)
	 */
	protected function usage()
	{
		echo <<<EOF
Command line utility for the Snaphe web extraction library.

Usage:
  Execute the wrapper.

    snaphe --wrapper=<name> [--format=(cli|xml|text|json|html|no)] [--no-output=(0|1)] [--file=...] [wrapper parameters...]

Options:
  --no-output             Surpress output.
  --format                Output format (cli|xml|text|json|html|no).
  --file                  Output file.

EOF;

		return 0;
	}

}
