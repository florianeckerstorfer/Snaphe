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
 * Represents a page.
 *
 * @package com.snaphe.page
 * @author Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 * @link http://snaphe.com Snaphe Web Data Extraction library for PHP.
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Snaphe_Page
{
	
	/** @var Snaphe_HTTP_Client; */
	protected $curlClient;
	
	/** @var array List of Snaphe_Model_Interface objects */
	protected $models = array();
	
	/** @var string */
	protected $url;
	
	/** @var array */
	protected $parameters;
	
	/** @var boolean */
	protected $post;
	
	/** @var boolean */
	protected $proxyServer = false;
	
	/**
	 * Constructor.
	 *
	 * @param string $url URL
	 */
	public function __construct($url, array $parameters = array(), $post = false, $proxyServer = false)
	{
		$this->url = $url;
		$this->parameters = $parameters;
		$this->post = $post;
		$this->proxyServer = $proxyServer;
	}
	
	/**
	 * Sets the cURL client.
	 *
	 * @param Snaphe_HTTP_Client $curlClient cURL client
	 * @return Snaphe_Page
	 */
	public function setCurlClient(Snaphe_HTTP_Client $curlClient)
	{
		$this->curlClient = $curlClient;
		return $this;
	}

	/**
	 * Returns the cURL client
	 *
	 * @return Snaphe_HTTP_Client cURL client
	 */
	public function getCurlClient()
	{
		return $this->curlClient;
	}
	
	/**
	 * Returns the source code of the page.
	 *
	 * @return string Source code of the page.
	 * @author Florian Eckerstorfer
	 */
	public function getSourceCode()
	{
		return $this->getResponse()->getBody();
	}
	
	/**
	 * Extracts all models from the page.
	 *
	 * @param string $url URL
	 * @param array $parameters Parameters (GET or POST)
	 * @param string $post If TRUE a POST request is executes, otherwise a GET request.
	 * @return boolean TRUE if all model extractors return TRUE, FALSE otherwise.
	 */
	public function extract($models)
	{
		$result = true;
		// Iterate through all models
		foreach ($models as $model)
		{
			// Extract model
			$result = $result && $model->extract($this->getSourceCode());
		}
		return $result;
	}
	
	/**
	 * Extract all blocks matching the given selector.
	 *
	 * @param Snaphe_Selector_Interface $selector Selector
	 * @return array Array with {@see Snaphe_Page_Block} objects.
	 */
	public function extractBlocks(Snaphe_Selector_Interface $selector)
	{
		$result = $selector->extract($this->getSourceCode());
		if (!is_array($result))
		{
			$result = array($result);
		}
		$blocks = array();
		foreach ($result as $blockResult)
		{
			$blocks[] = new Snaphe_Page_Block($blockResult);
		}
		return $blocks;
	}
	
	/**
	 * Extracts all forms of the given page.
	 *
	 * @return array Array with {@see Snaphe_Page_Form} objects.
	 */
	public function extractForms()
	{
		$forms = array();
		
		// Extract forms and iterate through them
		$formsSelector = new Snaphe_Selector_RegExp('/(<form(.*)>(.*)<\/form>)/sU', 1);
		$formBlocks = $this->extractBlocks($formsSelector);
		foreach ($formBlocks as $formBlock)
		{
			// Extract the form data
			$formModel = new Snaphe_Model();
			$formModel->addValue('action', new Snaphe_Selector_RegExp('/<form(.*)action="(.*?)"(.*)>/', 2))
					  ->addValue('method', new Snaphe_Selector_RegExp('/<form(.*)method="(.*?)"(.*)>/', 2));
			$formBlock->extract(array($formModel));
					
			// Extract input fields
			$inputs = $formBlock->extractBlocks(new Snaphe_Selector_RegExp('/(<input(.*)>)/sU', 1));
			$inputValues = array();
			foreach ($inputs as $inputBlock)
			{
				$inputModel = new Snaphe_Model();
				$inputModel->addValue('type', new Snaphe_Selector_RegExp('/type="(.*)"/U', 1))
						   ->addValue('value', new Snaphe_Selector_RegExp('/value="(.*)"/U', 1))
						   ->addValue('name', new Snaphe_Selector_RegExp('/name="(.*)"/U', 1));
				$inputBlock->extract(array($inputModel));
				$inputValues[] = array(
					'type'		=> $inputModel->getValue('type'),
					'value'		=> $inputModel->getValue('value'),
					'name'		=> $inputModel->getValue('name'),
				);
			}
			
			// Extract select fields
			$selects = $formBlock->extractBlocks(new Snaphe_Selector_RegExp('/(<select(.*)>(.*)<\/select>)/sU', 1));
			$selectValues = array();
			foreach ($selects as $selectBlock)
			{
				$selectModel = new Snaphe_Model();
				$selectModel->addValue('name', new Snaphe_Selector_RegExp('/<select(.*)name="(.*)"(.*)>/', 2))
							->addValue('options', new Snaphe_Selector_RegExp('/<option(.*)value="(.*)"(.*)>(.*)(<\/option>|\\n|\\r|\\t)/U', array(2, 4)))
							->addValue('value', new Snaphe_Selector_RegExp('/<option(.*)value="(.*)"(.*)selected(.*)>/U', 2))
							->addValue('value2', new Snaphe_Selector_RegExp('/<option(.*)selected(.*)value="(.*)"(.*)>/U', 3));
				$selectBlock->extract(array($selectModel));
				
				// Extract options
				$optionValues = array();
				$rawOptionValues = $selectModel->getValue('options');
				if (is_array($rawOptionValues))
				{
					foreach ($rawOptionValues as $option)
					{
						$name = null;
						if (isset($option[1]))
						{
							$name = $option[1];
						}
						if (isset($option[0]))
						{
							$optionValues[$option[0]] = $name;
						}
						else
						{
							$optionValues[] = $name;
						}
					}
				}

				$value = $selectModel->getValue('value');
				if (is_null($value))
				{
					$value = $selectModel->getValue('value2');
				}

				$selectValues[] = array(
					'name'		=> $selectModel->getValue('name'),
					'options'	=> $optionValues,
					'value'		=> $value,
					'type'		=> 'select'
				);
			}
			
			// Create form object
			$formValues = $formModel->getValues();
			$form = new Snaphe_Page_Form($formValues['action'], $formValues['method'], array_merge($inputValues, $selectValues));
			$forms[] = $form;
		}
		
		return $forms;
	}
	
	public function getResponse()
	{
		if ($this->post)
		{
			$response = $this->curlClient->executePost($this->url, $this->parameters, $this->proxyServer);
		}
		else
		{
			$response = $this->curlClient->executeGet($this->url, $this->parameters, $this->proxyServer);
		}
		return $response;
	}

}
