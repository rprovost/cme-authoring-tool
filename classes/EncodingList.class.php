<?php

include_once 'classes/Interfaces.php';
include_once 'classes/ElementList.class.php';

class EncodingList extends ElementList implements XMLable {

	public function addEncoding (CMXEncoding $encoding) {
		$this->elements[] = $encoding;
	}

	public function getEncodings() {
		return $this->elements;
	}

	public function asXML() {
		foreach ($this->elements AS $encoding) {
			$xml .= $encoding->asXML();
		}

		return $xml;
	}
}
