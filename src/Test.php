<?php
namespace Micorx\Welper;

include_once 'Handler/TestHandler.php';
use Micorx\Welper\Handler\TestHandler;

class Test {

	private $engine;

	private function start_engine() {
		if (! isset($this->engine) || ! $this->engine instanceof TestHandler) {
			$this->engine = new TestHandler();
		}
	}

	function test_youtube_video($link) {
		/**
		 *
		 * @param string $link
		 *        	Link of YouTube video
		 *
		 * @return $link: if the video exist
		 *         false: if is not possible to determine if the video exists
		 *
		 */
		$this->start_engine();
		if ($this->engine->e_test_youtube_video($link) !== false) {
			return $link;
		} else {
			return false;
		}
	}
}

?>