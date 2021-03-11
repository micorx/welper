<?php
include_once 'config/config.php';

include_once '../src/Html.php';

use Micorx\Welper\Html;

$picture_queries = array(
	array(
		'media' => 'min-width: 768px',
		'suffix' => 'l',
		'default' => true
	),
	array(
		'media' => '',
		'suffix' => 's'
	)
);
$image = '/test/resources/images/image_test';
$image_alt = 'Alt image';
$html = new Html();
$html->picture_set_options($picture_queries, false);
echo $html->picture_create($image, $image_alt);

$image = 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/80/Wikipedia-logo-v2.svg/1920px-Wikipedia-logo-v2.svg.png';
$image_alt = 'Alt image';
$html1 = new Html();
$html1->picture_set_options();
// echo 'Image 2';
// echo $html1->picture_create($image, $image_alt, true);
