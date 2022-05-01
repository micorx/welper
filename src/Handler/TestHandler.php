<?php

namespace Micorx\Welper\Handler;

class TestHandler
{

	private function s_test_link($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 500);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		$returned_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $returned_status_code;
	}

	function e_test_link($link, $http_code)
	{
		if (is_string($link) && is_int($http_code)) {
			if (str_starts_with($this->s_test_link($link), $http_code)) {
				return $link;
			}
		}
		return false;
	}

	function e_test_youtube_video($video, $is_full_link)
	{
		if (is_string($video)) {
			if ($is_full_link === true) {
				$link = 'https://www.youtube.com/oembed?format=json&url=' . $video;
			} else {
				$link = 'https://www.youtube.com/oembed?format=json&url=www.youtube.com/watch?v=' . $video;
			}
			$returned_status_code = $this->s_test_link($link);
			if (substr($returned_status_code, 0, 1) == '4' || substr($returned_status_code, 0, 1) == '5') {
				return false;
			} else {
				if ($is_full_link === true) {
					return $video;
				} else {
					return 'https://www.youtube.com/watch?v=' . $video;
				}
			}
		}
		return false;
	}
}
