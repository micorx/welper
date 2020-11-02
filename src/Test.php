<?php
namespace Micorx\Welper;

include_once 'Handler/TestEngine.php';
use Micorx\Welper\Internal\TestEngine;

class Test {

	private $engine;

	private function start_engine() {
		if (! isset($this->engine) || ! $this->engine instanceof TestEngine) {
			$this->engine = new TestEngine();
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