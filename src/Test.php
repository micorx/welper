<?php

namespace Micorx\Welper;

include_once 'Handler/TestHandler.php';

use Micorx\Welper\Handler\TestHandler;

class Test
{

	private $engine;

	private function start_engine()
	{
		if (!isset($this->engine) || !$this->engine instanceof TestHandler) {
			$this->engine = new TestHandler();
		}
	}

	function test_link($link, $http_code = 200)
	{
		/**
		 *
		 * @param string $link
		 *        	Link to test
		 * 
		 * @param string $http_code
		 *        	HTTP code to get, it is allowed the initial digits of the code
		 * 
		 * @return $link: if the link returns the http code requested
		 *         false: if the link NOT returns the http code requested
		 *
		 */
		$this->start_engine();
		if ($this->engine->e_test_link($link, $http_code) !== false) {
			return $link;
		} else {
			return false;
		}
	}

	function test_youtube_video($link, $is_full_link = false)
	{
		/**
		 *
		 * @param string $link
		 *        	Link or ID of YouTube video
		 * @param string $is_full_link
		 *        	true if it is the full link
		 *        	false if it contains just the video ID
		 *
		 * @return $link: if the video exist
		 *         false: if is not possible to determine if the video exists
		 *
		 */
		$this->start_engine();
		$link = $this->engine->e_test_youtube_video($link, $is_full_link);
		if ($link !== false) {
			return $link;
		} else {
			return false;
		}
	}
}
