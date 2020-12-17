<?php
include_once 'config/config.php';

include_once '../src/Html.php';

use Micorx\Welper\Html;

$tags = [
	'all-title' => 'title',
	'all-description' => 'description',
	'm-telephone-no' => true,
	'm-x-ua-compatible' => true,
	'm-charset' => 'utf-8',
	'm-viewport' => 'width=device-width, initial-scale=1, viewport-fit=cover',
	'm-robots' => 'index, follow',
	'm-author' => 'author',
	'm-og-type' => 'website',
	'm-og-site' => 'site.com',
	'm-tw-card' => 'twitter_card',
	'm-tw-site' => 'twitterusername',
	'm-fb-appid' => '493039404',
	'm-og-locale' => 'it_IT',
	'm-og-locale-alt' => [
		'en_US'
	],
	'm-canonical' => [
		'uri' => '/test/Head.php',
		'uri-type' => 'int'
	],
	'm-og-url' => [
		'uri' => '/test/Head.php',
		'uri-type' => 'int'
	],
	'l-alternate' => [
		[
			'hreflang' => 'it',
			'uri' => '/path/to/page',
			'uri-type' => 'int',
			'default' => true
		],
		[
			'hreflang' => 'en',
			'uri' => '/en/path/to/page',
			'uri-type' => 'int'
		]
	],
	'm-og-image' => [
		'uri' => '/test/resources/images/image_test-s.jpg',
		'uri-type' => 'int',
		'alt' => 'Text'
	],
	'm-tw-image' => [
		'uri' => '/test/resources/images/image_test-s.jpg',
		'uri-type' => 'int',
		'alt' => 'Text'
	],
	'l-favicon' => [
		[
			'rel' => 'tapple-touch-icon',
			'sizes' => '180x180',
			'uri' => '/test/resources/images/image_test-s.jpg',
			'uri-type' => 'int'
		],
		[
			'rel' => 'icon',
			'type' => 'image/jpg',
			'sizes' => '32x32',
			'uri' => '/test/resources/images/image_test-s.jpg',
			'uri-type' => 'int'
		],
		[
			'rel' => 'icon',
			'type' => 'image/jpg',
			'sizes' => '16x16',
			'uri' => '/test/resources/images/image_test-s.jpg',
			'uri-type' => 'int'
		]
	],
	'l-css' => [
		'/test/resources/assets/css/test1.css',
		[
			'uri' => '/test/resources/assets/css/test2.css',
			'uri-type' => 'int'
		]
	],
	's-js' => [
		'/test/resources/assets/js/test1.js',
		[
			'uri' => '/test/resources/assets/js/test2.js',
			'uri-type' => 'int'
		]
	]
];
$order = [
	'm-x-ua-compatible',
	'title',
	'm-title',
	'l-favicon',
	'm-charset',
	'm-viewport',
	'm-robots',
	'm-telephone-no',
	'm-description',
	'm-author',
	'm-og-title',
	'm-og-description',
	'm-og-type',
	'm-og-site',
	'm-tw-title',
	'm-tw-description',
	'm-tw-card',
	'm-tw-site',
	'm-fb-appid',
	'm-canonical',
	'm-og-url',
	'm-og-locale',
	'm-og-locale-alt',
	'l-alternate',
	'm-og-image',
	'm-tw-image',
	'l-css',
	's-js'
];

$html = new Html();
$head_arr = $html->head_set_tag($tags);
$head = $html->head_create($order);
$head_no = $html->head_create();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<?php echo $head; ?>
</head>

<body>
	TEST
	<?php var_dump($tags); ?>
	<?php var_dump($head_arr); ?>
	<?php var_dump($head); ?>
	<?php var_dump($head_no); ?>
</body>

</html>