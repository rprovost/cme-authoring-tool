<?php

class ElementList {

	protected $elements;

	function __construct() {
		$this->elements = Array();
	}

	public function getElements() {
		return $this->elements;
	}

	public function addElement($element) {
		$this->elements[] = $element;
	}
}
