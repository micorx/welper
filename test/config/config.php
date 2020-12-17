<?php
define('DOCUMENT_ROOT_CUSTOM', $_SERVER['DOCUMENT_ROOT'] . '/welper');
define(
	'REMOTE_DOMAIN_CUSTOM',
	strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))) . '://' . $_SERVER['HTTP_HOST'] . '/welper'
);
