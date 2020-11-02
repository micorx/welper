<?php
namespace Micorx\Welper\Handler;

class TestHandler {

	function e_test_youtube_video($link) {
		$ch = curl_init('https://www.youtube.com/oembed?format=json&url=' . $link);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		curl_exec($ch);
		$returned_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if (substr($returned_status_code, 0, 1) == '4' || substr($returned_status_code, 0, 1) == '5') {
			return false;
		} else {
			return true;
		}
	}
}

?>