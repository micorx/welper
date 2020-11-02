<?php
include_once 'config/config.php';

include_once '../src/Html.php';
use Micorx\Welper\Html;

$tags = array(
	'm-telephone-no' => true,
	'm-x-ua-compatible' => true,
	'title' => 'title',
	'm-title' => 'title',
	'm-charset' => 'utf-8',
	'm-viewport' => 'width=device-width, initial-scale=1, viewport-fit=cover',
	'm-robots' => 'index, follow',
	'm-description' => 'description',
	'm-author' => 'author',
	'm-og-title' => 'title',
	'm-og-description' => 'description',
	'm-og-type' => 'website',
	'm-og-site' => 'site.com',
	'm-tw-title' => 'title',
	'm-tw-description' => 'description',
	'm-tw-card' => 'twitter_card',
	'm-tw-site' => 'twitterusername',
	'm-fb-appid' => '493039404',
	'm-locale' => array(
		array(
			'lang' => 'it_IT'
		),
		array(
			'lang' => 'en_US',
			'alternate' => true
		)
	),
	'm-canonical' => array(
		'uri' => '/test/Head.php',
		'uri-type' => 'internal'
	),
	'm-og-url' => array(
		'uri' => '/test/Head.php',
		'uri-type' => 'internal'
	),
	'l-alternate' => array(
		array(
			'hreflang' => 'it',
			'uri' => '/path/to/page',
			'uri-type' => 'internal',
			'default' => true
		),
		array(
			'hreflang' => 'en',
			'uri' => '/en/path/to/page',
			'uri-type' => 'internal'
		)
	),
	'm-og-image' => array(
		'uri' => '/test/resources/images/image_test-s.jpg',
		'uri-type' => 'internal',
		'alt' => 'Text'
	),
	'm-tw-image' => array(
		'uri' => '/test/resources/images/image_test-s.jpg',
		'uri-type' => 'internal',
		'alt' => 'Text'
	),
	'l-favicon' => array(
		array(
			'rel' => 'tapple-touch-icon',
			'sizes' => '180x180',
			'uri' => '/test/resources/images/image_test-s.jpg',
			'uri-type' => 'internal'
		),
		array(
			'rel' => 'icon',
			'type' => 'image/jpg',
			'sizes' => '32x32',
			'uri' => '/test/resources/images/image_test-s.jpg',
			'uri-type' => 'internal'
		),
		array(
			'rel' => 'icon',
			'type' => 'image/jpg',
			'sizes' => '16x16',
			'uri' => '/test/resources/images/image_test-s.jpg',
			'uri-type' => 'internal'
		)
	),
	'l-css' => array(
		array(
			'uri' => '/test/resources/assets/css/test.css',
			'uri-type' => 'internal'
		)
	),
	'l-js' => array(
		array(
			'uri' => '/test/resources/assets/js/test.js',
			'uri-type' => 'internal'
		)
	)
);
$order = array(
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
	'm-locale',
	'l-alternate',
	'm-og-image',
	'm-tw-image',
	'l-css',
	'l-js'
);

$html = new Html();
$head_arr = $html->head_set_tag($tags);
$head = $html->head_create($order);
$head_no = $html->head_create();


?>
<!DOCTYPE html>
<html lang="fr">
<head>
<?php echo $head;?>
</head>
<body>
TEST
<?php var_dump($tags);?>
<?php var_dump($head_arr);?>
<?php var_dump($head);?>
<?php var_dump($head_no);?>
</body>
</html>