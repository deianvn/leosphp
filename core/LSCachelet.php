<?php

namespace ls\core;

class LSCachelet {
	private $scriptlet;
	private $snippet;
	private $params;
	
	public function __construct($scriptlet, $snippet, $params) {
		$this->scriptlet = $scriptlet;
		$this->snippet = $snippet;
		$this->params = $params;
	}
	
	public function load() {
	
	}
	
	public function save() {
	
	}
}
