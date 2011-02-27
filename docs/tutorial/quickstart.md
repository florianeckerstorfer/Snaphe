A Quick Introduction to Snaphe
==============================

Preamble
--------

Snaphe is a Web Data Extraction library written in PHP. This is a quick introduction.

Installation
------------

There is no installation needed, just copy Snaphe to your hard disk (or a server).

Snaphe Command Line Interface
-----------------------------
	
Snaphe offers a CLI tool which executes a wrapper. You can call his tool manually or using a cron job.

Before you can start the CLI tool you need to add execution rights to the file "snaphe".

	chmod +x snaphe
	
You can define the directory where you want to store your wrappers. If you don't define the  `SNAPHE_WRAPPER_DIRECTORIES` environment variable Snaphe searches for wrappers in the `./wrappers` directory.

	export SNAPHE_WRAPPER_DIRECTORIES="/path/to/your/wrappers"
	
There are already some example wrappers in the `./wrappers` directory. For example the `GoogleSearchWrapper` performs a search request on Google and extracts the result page.

	./snaphe --wrapper=GoogleSearch --q="web data extraction"
	
The first argument is the name of the wrapper, the second argument is the search query.
	
By default Snaphe prints the result in a format which makes it easy to read by a human. When you are using the CLI tool (which is powered by the class `Snaphe_Application_Cli`) this format is `cli`. The HTTP application (`Snaphe_Application_HTTP`) uses by default the `html` format. If you want to reuse the data in another application you can use the `xml` or `json` format.

	./snaphe --wrapper=GoogleSearch --q="web data extraction" --format=cli
	./snaphe --wrapper=GoogleSearch --q="web data extraction" --format=html
	./snaphe --wrapper=GoogleSearch --q="web data extraction" --format=xml
	./snaphe --wrapper=GoogleSearch --q="web data extraction" --format=json
	./snaphe --wrapper=GoogleSearch --q="web data extraction" --format=text
	
You can save the results by adding the argument `file`:

	./snaphe --wrapper=GoogleSearch --q="web data extraction" --format=xml --file=/home/data/googlesearch.xml
	
If you just want to execute a wrapper, you can surpress the output be choosing `no` as format. Please note that this also affects the `file` argument.

	./snaphe --wrapper=GoogleSearch --q="web data extraction" --format=no

What is a Wrapper?
------------------

A wrapper is a PHP class which implements the `Snaphe_Wrapper_Interface` and contains code to navigate through a website and extract data. Because the interface requires the wrapper to implement some common methods there is an abstract class `Snaphe_Wrapper_Abstract` which has all common methods already implemented.

`Snaphe_Wrapper_Interface` defines three methods which must be implemented by every wrapper.

	public function execute();
	public function getModels();
	public function getUrls();
	
If you choose to use `Snaphe_Wrapper_Abstract` you only need to implement the method `execute()`.

Before we can actually start writing a wrapper, we need to define some terms.

### Selector

A selector is an object that implements the `Snaphe_Selector_Interface`. It extracts exactly one kind of value from a given string, for example all prices or the page title. There are three different types of selectors:

 - `Snaphe_Selector_XPath`
 - `Snaphe_Selector_RegExp`
 - `Snaphe_Selector_CSS`

### Model

A model is a collection of selectors which belong together. For example when extraing data from a web shop, there could be an article model which contains selectors for the title, description and price. There are different kinds of models:

 - `Snaphe_Model` for single entities
 - `Snaphe_Model_List` for list of entities

### Page

A page is an instance of `Snaphe_Page` and is defined by exactly one URL. You can specify one or more models that should be extracted from that page.

### Client

Snaphe uses `Snaphe_HTTP_Client` as a wrapper for PHPs cURL library. This class executes the HTTP request and makes it easy to define GET and POST parameters, cookies and faking the referer or the user agent.

Writing a Wrapper
-----------------

At first we need to create a class which implements `Snaphe_Wrapper_Interface`

	class GoogleSearchWrapper extends Snaphe_Wrapper_Abstract
	{


		public function __construct(array $parameters)
		{
		}

		public function execute()
		{
		}

	}
	
Next we need to define which parameters the wrapper accepts. By default a parameter is optional, but you can make them required:

	protected $definedParameters = array('foo', 'bar');
	protected $requiredParameters = array('foo');

In this example there are two parameters `foo` and `bar` and foo is required, while `bar` is optional.

Snaphe passes the parameters to the wrapper through the constructor. `Snaphe_Wrapper_Abstract` offers a method to
check these parameters:

	public function __construct(array $parameters)
	{
		$this->initializeParameters($parameters);
	}
	
The goal of this first wrapper is to extract the title and description from the Google search result page.

At first we need to create a `Snaphe_HTTP_Client` object, which will be used as a HTTP client. We can set these client as the default client.

	public function execute()
	{
		$client = new Snaphe_HTTP_Client();
		$this->setDefaultClient($client);
	}

Next we need to create a page object. We could instaniate `Snaphe_Page` and then specify the HTTP client, the URL and so on but it is simpler to use the `getPage()` method of `Snaphe_Wrapper_Abstract`.

	$page = $this->getPage('http://www.google.com/search?q=' . urlencode($this->getParameter('q')));
	
Next we need to define a model and some selectors. We want to define 2 selectors using Regular Expressions.

	$model = new Snaphe_Model_List();
	$titleRegExp = new Snaphe_Selector_RegExp('/<h3(.*)>(.*)<\/h3>/Us', 2);
	$titleRegExp->registerCallback('my_strip_tags');
	$model->addValue('title', $titleRegExp);
	$descriptionRegExp = new Snaphe_Selector_RegExp('/<div class=\"s\">(.*)<br>/Us');
	$descriptionRegExp->registerCallback('my_strip_tags');
	$model->addValue('description', $descriptionRegExp);

Let's take a closer look at the first selector:

	$titleRegExp = new Snaphe_Selector_RegExp('/<h3(.*)>(.*)<\/h3>/Us', 2);
	$titleRegExp->registerCallback('my_strip_tags');
	
The first argument of the constructor is a standard PHP regular expression. The second argument is the index of the
match in the regular expression. In that example it would extract the inner HTML of H3 tag and not the attributes.
Next we define a callback function which is applied to every extracted value. `my_strip_tags()` is a wrapper for the
standard PHP function `strip_tags()`, but it also takes an array as a argument. Here is the code:

	function my_strip_tags($value)
	{
		if (is_array($value))
		{
			for ($i = 0; $i < count($value); ++$i)
			{ 
				$value[$i] = strip_tags($value[$i]);
			}
			return $value;
		}
		return strip_tags($value);
	}

There is one more thing to do. Extract the models and save them in the property `$models`:

	$page->extract(array($model));
	$this->models[] = $model;

Note: The `extract()` method takes an array as argument. You can define is much models as you want. Also the source code of the defined website is fetched on demand (if `extract()` is never called, no HTTP request is executed).

You can now execute the wrapper:

	./snaphe --wrapper=GoogleSearch --q="web data extraction"
	
Working with Cookies
--------------------

One cool features of Snaphe is that you can easily work with cookies. For example, if the wrapper needs to login into a website to extract data, you can use a code similar to this one:

	$page = $this->getPage('http://echo.pckg.org/login.php', array(
		'login' => 1,
		'username' => $this->getParameter('username'),
		'password' => $this->getParameter('password'),
	), true);
	$client->setCookies($page->getResponse()->getCookies());
	
We do two things here:

 1. We execute a POST request including two parameters (the login data)
 2. The third parameter of `getPage()` indicates if a POST (`true`) or a GET (`false`, default) is executed.
 3. Extract the cookies from the response and set it to the client. The client will send the cookies to the server on every upcoming request.

HTTP Interface
--------------

If you wish you can also execute wrappers through the HTTP application. You need to setup your webserver to make the public directory callable through an URL. If you specify `json` or `xml` as output format Snaphe will serve as an API for extracting data from websites.

	http://api.snaphe.com/?wrapper=GoogleSearch&q=web+data+extraction&format=xml

Custom Output Adapters
----------------------

Besides the default output formats you can also write a custom output adapter. This is useful for example when you want to write the extracted data directly into a database. An output adapter must implement the `Snaphe_Output_Interface`. This interface requires you to implement nine methods but you can use `Snaphe_Output_Abstract` which implements eight of them. You only need to write the `render()` method. This method must return the output as a single string if it should be possible to use it in combination with the `file` argument but can also return `NULL` or some kind of status message if the saving process is done inside the `render()` method. `Snaphe_Output_Abstract` offers several methods which you can use to retrieve the data.

	getName()
	getUrl()
	getInputParameters()
	getModels()

Snaphe does not include the file containing the output class, you need to include it before executing the wrapper or by implementing or using your own autoloader. If you have done this, you can execute the wrapper:

	./snaphe --wrapper=GoogleSearch --q="web data extraction" --output-adapter=MySQL
	
	http://api.snaphe.com/?wrapper=GoogleSearch&q=web+data+extraction&output-adpater=MySQL

Working with Forms
------------------

If you need to extract values from a form you can use the `getForms()` method implemented by `Snaphe_Page`. This method returns all forms as `Snaphe_Page_Form` object.

	$page = $this->getPage($url);
	$forms = $page->getForms();
	
Working with Blocks of HTML Code
--------------------------------

If you don't want to extract a single value you can also extract a block of HTML code by using `extractBlocks()` implemented by `Snaphe_Page`. If returns a `Snaphe_Page_Block` object which is a subclass of `Snaphe_Page`.

	$page = $this->getPage($url);
	$blocks = $page->extractBlocks(new Snaphe_Selector_XPath('/article'));

Integrate Snaphe in your Application
------------------------------------

If you don't want to use the CLI or HTML application you can subclass `Snaphe_Application_Abstract` and create your own application. You can define the property `$default_output_format` to define the default output for all your wrappers. You can execute your wrapper by calling the `run()` method of `Snaphe_Application_Abstract` which requires an array with all arguments as parameter.



