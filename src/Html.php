<?php
namespace Micorx\Welper;

include_once 'Handler/HtmlHandler.php';
use Micorx\Welper\Handler\HtmlHandler;

class Html {

	private $engine;

	private $head;

	private $picture;

	function __construct() {
		$this->head = false;
		$this->picture = false;
	}

	private function start_engine() {
		if (! isset($this->engine) || ! $this->engine instanceof HtmlHandler) {
			$this->engine = new HtmlHandler();
		}
	}

	/* **************************************
	 **************** HEAD ******************
	 **************************************** */
	function head_set_tag($values) {
		/**
		 *
		 * @param array $values
		 *        	Associative array of values of head tags
		 *        
		 *          -----------
		 *        	-- FIXED --
		 *          -----------
		 *        	• 'm-telephone-no' 		->	[meta]				->	disable auto-detection of mobile phones
		 *        	• 'm-x-ua-compatible' 	->	[meta]				->	compatibility with internet explorer
		 *        
		 *        	- Type: Boolean
		 *        
		 *        
		 *          ------------
		 *        	-- STRING --
		 *          ------------
		 *        	• 'title' 				->	[title]				->	title of page
		 *        	• 'm-title' 			->	[meta]				->	page title
		 *        	• 'm-charset'			->	[meta]				->	charset
		 *        	• 'm-viewport' 			->	[meta]				->	viewport
		 *        	• 'm-robots' 			->	[meta]				->	robots
		 *        	• 'm-description' 		->	[meta]				->	description
		 *        	• 'm-author' 			->	[meta]				->	author of this document
		 *        	• 'm-og-title' 			->	[meta - og]			->	page title
		 *        	• 'm-og-description' 	->	[meta - og]			->	description
		 *        	• 'm-og-type' 			->	[meta - og]			->	type of document (ex. website)
		 *        	• 'm-og-site' 			->	[meta - og]			->	site name
		 *        	• 'm-tw-title' 			->	[meta - twitter]	->	page title
		 *        	• 'm-tw-description' 	->	[meta - twitter]	->	desctiption - max 420 chars
		 *        	• 'm-tw-card' 			->	[meta - twitter]	->	card: type of post
		 *        	• 'm-tw-site' 			->	[meta - twitter]	->	twitter username
		 *        	• 'm-fb-appid' 			->	[meta - facebook]	->	app id
		 *        	
		 *        	- Type: String
		 *        
		 *        
		 *          ------------
		 *        	-- LOCALE --
		 *          ------------
		 *        	• 'm-og-locale' 		->	[meta - og]			->	locale of this page
		 *        
		 *        	- Type: String
		 *        
		 *        	• 'm-og-locale-alt' 	->	[meta - og]			->	all locale alternate for this page
		 *        
		 *        	- Type: Array
		 *        	 | each element is a language and location abbreviation (ex. en_US) for this page
		 *        
		 *                  
		 *          ---------
		 *        	-- URI --
		 *          ---------
		 *        	• 'm-canonical' 		->	[meta]				->	canonical page
		 *        	• 'm-og-url' 			->	[meta - og]			->	canonical page
		 *        
		 *        	- Type: Associative Array
		 *        	|- Parameters:
		 *        	| • 'uri'			-->	'/full/path/to/page'						->	internal resource, starts with '/'
		 *        	|					|->	'http://www.site.tld/path/to/page'			->	full url
		 *          |
		 *        	| • 'uri-type'		-->	'internal'									->	uri is an internal resource
		 *        	|					|->	'external' [default - if not defined]		->	uri is an external link
		 *        	
		 *        	• 'm-og-image' 			->	[meta - og]			->	image, dimensions are calculated
		 *        	• 'm-tw-image' 			->	[meta - twitter]	->	image
		 *        
		 *        	- Type: Associative Array
		 *        	|- Parameters:
		 *        	| • 'uri'			-->	'/full/path/to/image.extension'				->	internal resource, starts with '/'
		 *        	|					|->	'http://www.site.tld/path/to/image.ext'		->	full url
		 *          |
		 *        	| • 'uri-type'		-->	'internal'									->	uri is an internal resource
		 *        	|					|->	'external' [default - if not defined]		->	uri is an external link
		 *          |
		 *        	| • 'alt'			->	text alternate for img
		 *        	    
		 *        	• 'l-alternate'			->	[link]				->	all alternate pages
		 *        
		 *        	- Type: Array
		 *        	|- Element: Associative Array
		 *        	 |- Parameters:
		 *        	 | • 'uri'			-->	'/full/path/to/page'						->	internal resource, starts with '/'
		 *         	 |					|->	'http://www.site.tld/path/to/page'			->	full url
		 *           |
		 *        	 | • 'uri-type'		-->	'internal'									->	uri is an internal resource
		 *        	 |					|->	'external' [default - if not defined]		->	uri is an external link
		 *           |
		 *        	 | • 'hreflang'		->	language abbreviation (ex. en)
		 *        	 | • 'default'		->	true, if it is the language default for this page
		 *        
		 *        	• 'l-favicon' 			->	[link]				->	all favicons
		 *        
		 *        	- Type: Array
		 *        	|- Element: Associative Array
		 *        	 |- Parameters:
		 *        	 | • 'uri'			-->	'/full/path/to/image.extension'				->	internal resource, starts with '/'
		 *         	 |					|->	'http://www.site.tld/path/to/image.ext'		->	full url
		 *           |
		 *        	 | • 'uri-type'		-->	'internal'									->	uri is an internal resource
		 *        	 |					|->	'external' [default - if not defined]		->	uri is an external link
		 *           |
		 *        	 | • 'rel'			->	type of favicon
		 *        	 | • 'type'			->	type of image
		 *        	 | • 'sizes'			->	dimension of image
		 *        
		 *        	• 'l-css' 				->	[link]				->	all css files
		 *        
		 *        	- Type: Array
		 *        	|- Element: String
		 *        	 | '/full/path/to/file.css'		->	it MUST be an internal resource, starts with '/'
		 *        	|- Element: Associative Array
		 *        	 |- Parameters:
		 *        	 | • 'uri'			-->	'/full/path/to/file.css'					->	internal resource, starts with '/'
		 *         	 |					|->	'http://www.site.tld/path/to/file.css'		->	full url
		 *           |
		 *        	 | • 'uri-type'		-->	'internal'									->	uri is an internal resource
		 *        	 |					|->	'external' [default - if not defined]		->	uri is an external link
		 *        
		 *        	• 's-js' 				->	[script]			->	all js files
		 *        	
		 *        	- Type: Array
		 *        	|- Element: String
		 *        	 | '/full/path/to/file.css'		->	it MUST be an internal resource, starts with '/', no option allowed
		 *        	|- Element: Associative Array
		 *        	 |- Parameters:
		 *        	 | • 'uri'			-->	'/full/path/to/file.js'						->	internal resource, starts with '/'
		 *         	 |					|->	'http://www.site.tld/path/to/file.js'		->	full url
		 *           |
		 *        	 | • 'uri-type'		-->	'internal'									->	uri is an internal resource
		 *        	 |					|->	'external' [default - if not defined]		->	uri is an external link
		 *           |
		 *        	 | • 'defer'		->	true if the script is defer
		 *        	 | • 'async'		->	true if the script is async
		 *        
		 *        
		 *          --------------
		 *        	-- MULTIPLE --
		 *          --------------
		 *        	• 'all-title'			->	title of page
		 *        	
		 *        	- Applied to:
		 *        	• 'title'
		 *        	• 'm-title'
		 *        	• 'm-og-title'
		 *        	• 'm-tw-title'
		 *        
		 *        	• 'all-description' 	->	description
		 *        	
		 *        	- Applied to:
		 *        	• 'm-description'
		 *        	• 'm-og-description'
		 *        	• 'm-tw-description'
		 *        
		 *        
		 *        	• 'all-image' 			->	image
		 *        	
		 *        	- Applied to:
		 *        	• 'm-og-image'
		 *        	• 'm-tw-image'
		 *        
		 *        	
		 * @return boolean true: if the values not contain errors
		 *         boolean false: if the values contain errors
		 *        
		 */
		$this->start_engine();
		$tags = $this->engine->e_head_validate_tag($this->head, $values);
		if ($tags !== false) {
			$this->head = $tags;
			return $tags;
		} else {
			return false;
		}
	}

	function head_create($order = false) {
		/**
		 *
		 * @param array $order
		 *        	Array with the list of heaf tag to insert in head section
		 *        	If not specified the order used is the order defined in method head_set_tag()
		 *
		 * @return true: if the are no error
		 *         false: if there are no tag values setted or head_compose() found some error
		 *
		 */
		$this->start_engine();
		if (! isset($this->head)) {
			return false;
		}
		$html = $this->engine->e_head_compose($this->head, $order);
		if ($html !== false) {
			return $html;
		} else {
			return false;
		}
	}

	/* *****************************************
	 ***************** PICTURE *****************
	 ******************************************* */
	function picture_set_options($media_queries = true, $lazy = false, $id = null, $classes = null, $properties = null) {
		/**
		 *
		 * @param array $media_queries
		 *        	Array of media queries
		 *        	
		 *        
		 *          -----------
		 *        	-- FIXED --
		 *          -----------
		 *        	• 'default' 			->	[default: false]	->	true if this is the default image
		 *        
		 *        	- Type: Boolean
		 *        
		 *        
		 *          ------------
		 *        	-- STRING --
		 *          ------------
		 *        	• 'media' 				->	[default: '']		->	media query
		 *        	• 'separator'			->	[default: '-']		->	character between the file name and suffix
		 *        	• 'suffix' 				->	[default: '']		->	suffix to image with respective query
		 *        	• 'format' 				->	[default: jpg]		->	file format -  allowed: jpg, png, svg, webp
		 *        
		 *        	- Type: String
		 *        
		 *        	
		 * @param boolean $lazy
		 *        	If true, helper add settings to lazy loading images
		 *        	Class "image-lazy-loading"
		 *        	
		 * @param string $id
		 *        	Name of id
		 *        	No spaces admitted
		 *        	
		 * @param string $classes:
		 *        	Names classes separated by single space
		 *        	
		 * @param string $properties
		 *        	Other properies to add to picture tag
		 *        
		 *        	!! IMPORTANT !!
		 *        	No security check on this parameter
		 *        	
		 * @return true: if the are no error on validation
		 *         false: if data contains some errors
		 *        
		 */
		$this->start_engine();
		$options = $this->engine->e_picture_validate($media_queries, $lazy, $id, $classes, $properties);
		if ($options !== false) {
			$this->picture = $options;
			return true;
		} else {
			return false;
		}
	}

	function picture_create($image_path, $image_alt = '', $image_external = false) {
		/**
		 *
		 * @param string $image_path
		 *        	Path to image file:
		 *        	"/path/to/image"
		 *        	
		 *        	Rules:
		 *        	- not include any subfixes and separators
		 *        	- not include the server http host
		 *        	
		 *        	Example of structure in root:
		 *        	
		 *        	/media/images/test/image-l.jpg
		 *        	/media/images/test/image-s.jpg
		 *        	
		 *        	!! IMPORTANT !!
		 *        	If lazy option is true all <img> and <source> tag have 'data-src' instead 'src'
		 *        	To enable the loading of images -> change, using script client-side, 'data-src' to 'src'.
		 *    
		 * @param string $image_alt
		 *        	Alternative text for image
		 *        	Default: empty string
		 *        
		 * @param string $image_external
		 *        	- false		->	image is on server
		 *        	- true		->	image is an external resource
		 *        	Default: false
		 *        	  	
		 * @return true: if the are no error
		 *         false: if there are no option setted or picture_compose() found some error
		 *        
		 */
		$this->start_engine();
		if (! isset($this->picture)) {
			return false;
		}
		$html = $this->engine->e_picture_compose($this->picture, $image_path, $image_alt, $image_external);
		if ($html !== false) {
			return $html;
		} else {
			return false;
		}
	}
}
?>