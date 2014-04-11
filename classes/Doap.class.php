<?php

include_once 'classes/Interfaces.php';

class Doap implements XMLable {

	protected $name;
	protected $url;
	protected $created;
	protected $version;
	protected $modified;

	function set($attrib, $value) {
		$this->$attrib = $value;
	}

	// This is the modified and current output for the DOAP namespace
	function asXML() {
		if ( strlen($this->name)>0 && strlen($this->version)>0 ) {
			$xml .= '<cmxadmin:modificationHistory>';
			$xml .= '<cmxadmin:ModificationHistory dc:created="'.$this->created.'">';
			$xml .= '<cmxadmin:generatedBy rdf:resource="'.$this->url.'/#'.$this->version.'"/>';
			$xml .= '</cmxadmin:ModificationHistory>';
			$xml .= '</cmxadmin:modificationHistory>';

			return $xml;
		} else {
			return false;
		}
	}
}
