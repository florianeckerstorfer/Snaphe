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
 * Abstract application.
 *
 * @package com.snaphe.application
 * @copyright 2011 Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @link http://florianeckerstorfer.com Florian Eckerstorfer
 * @link http://2bepublished.at Development powered by 2bePUBLISHED Internet Services Austria GmbH
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
abstract class Snaphe_Application_Abstract
{
	
	/** @var array */
	protected $wrapper_directories = array();
	
	/** @var array */
	protected $output_formats = array('text', 'xml', 'html', 'no', 'json');	

	/**
	 * Constructor.
	 *
	 * @param string $wrapper_directories PATH_SEPARATOR separated string with directories to search for wrappers.
	 */
	public function __construct($wrapper_directories)
	{
		if (0 == strlen(trim($wrapper_directories)))
		{
			throw new Snaphe_Exception('No wrapper directories specified.');
		}
		$this->wrapper_directories = explode(PATH_SEPARATOR, $wrapper_directories);
	}
	
	/**
	 * Runs the application.
	 *
	 * @param array $args Arguments
	 * @return integer Exit code
	 */
	public function run(array $args)
	{
		if (!isset($args['wrapper']) || strlen($args['wrapper']) == 0)
		{
			return $this->usage();
		}
		
		if (isset($args['no-output']))
		{
			if ('true' == $args['no-output'] || 1 == $args['no-output'])
			{
				$args['format'] = 'no';
			}
			unset($args['no-output']);
		}
		
		$file = null;
		if (isset($args['file']) && $args['file'])
		{
			$file = $args['file'];
			unset($args['file']);
		}
		
		if (isset($args['output-class']))
		{
			$output_class = $args['output-class'];
			unset($args['output-class']);
		}
		elseif (isset($args['format']))
		{
			if (!in_array($args['format'], $this->output_formats))
			{
				return $this->usage();
			}
			$output_class = 'Snaphe_Output_' . ucfirst($args['format']);
			unset($args['format']);
		}
		else
		{
			$output_class = $this->default_output_class;
		}
		
		$wrapper_name = $args['wrapper'] . 'Wrapper';
		unset($args['wrapper']);
		$wrapper = $this->execute($wrapper_name, $args);
		$result = new $output_class();
		if (!($result instanceof Snaphe_Output_Interface))
		{
			throw new Snpahe_Exception('Given output class is not an instance of Snaphe_Output_Interface.'); 
		}
		$result->setName($wrapper_name)
			   ->setUrl($wrapper->getUrls())
			   ->setInputParameters($args)
			   ->setModels($wrapper->getModels());
		$output = $result->render();
		echo $output;
		if ($file)
		{
			file_put_contents($file, $output);
		}
		
		return 0;
	}
	
	/**
	 * Returns an array with all wrappers.
	 *
	 * @return array Array with all wrappers.
	 */
	protected function getWrappers()
	{
		$wrappers = array();
		foreach ($this->wrapper_directories as $directory)
		{
			$iterator = new DirectoryIterator(trim($directory));
			foreach ($iterator as $file)
			{
				if ('Wrapper.php' == substr($file->getFilename(), -11))
				{
					$wrappers[] = $file->getPathname();
				}
			}
		}
		return $wrappers;
	}
	
	/**
	 * Returns the name of the wrapper.
	 *
	 * @param string $wrapper_filename Wrapper filename.
	 * @return string Wrapper name.
	 */
	protected function getWrapperName($wrapper_filename)
	{
		return substr($wrapper_filename, strrpos($wrapper_filename, '/') + 1, -4);
	}

	/**
	 * Returns the filename of the given wrapper.
	 *
	 * @param string $wrapper_name Wrapper name or NULL when no wrapper exists with the given name.
	 * @return string Wrapper filename
	 */
	protected function getWrapperFilename($wrapper_name)
	{
		foreach ($this->wrapper_directories as $directory)
		{
			$iterator = new DirectoryIterator(trim($directory));
			foreach ($iterator as $file)
			{
				if ($wrapper_name == $this->getWrapperName($file->getPathname()))
				{
					return $file->getPathname();
				}
			}
		}
		
		// Return NULL when no wrapper exists with the given name.
		return null;
	}
	
	/**
	 * Executes the given wrapper and returns the models.
	 *
	 * @param string $wrapper_name Wrapper name
	 * @return array Array with models of the given wrapper.
	 * @throws Snaphe_Exception when the wrapper file could not be found.
	 */
	public function execute($wrapper_name, $args)
	{
		$wrapper_filename = $this->getWrapperFilename($wrapper_name);
		if (!$wrapper_filename)
		{
			throw new Snaphe_Exception('Could not find wrapper "' . $wrapper_name . '".');
		}
		return $this->executeWrapper($wrapper_filename, $args);
	}
	
	/**
	 * Executes the given wrapper and returns the result.
	 *
	 * @param string $wrapper_filename Wrapper filename
	 * @return array Array with models of the executed wrapper.
	 * @throws Snaphe_Exception when the wrapper file does not exists or is not readable.
	 * @throws Snaphe_Exception when the wrapper class is not an instance of Snaphe_Wrapper_Interface
	 */
	protected function executeWrapper($wrapper_filename, $args)
	{
		// Check if the wrapper file exists and include it.
		if (!file_exists($wrapper_filename) || !is_file($wrapper_filename) || !is_readable($wrapper_filename))
		{
			throw new Snaphe_Exception('Could not open wrapper file "' . $wrapper_filename . '".');
		}
		require_once $wrapper_filename;
		
		// Create object and check it.
		$wrapper_name = $this->getWrapperName($wrapper_filename);
		$wrapper = new $wrapper_name($args);
		if (!($wrapper instanceof Snaphe_Wrapper_Interface))
		{
			throw new Exception('Wrapper class must be an instance of Snaphe_Wrapper_Interface.');
		}
		
		// Execute wrapper
		$wrapper->execute($args);
		
		// Return models of the wrapper.
		return $wrapper;
	}

}
