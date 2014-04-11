<?php

include_once 'classes/Interfaces.php';
include_once 'classes/ElementList.class.php';

class GroupedList extends ElementList implements XMLable {

	public function getAssets() {
		$assets = array();

		// loop through groups
		if (count($this->elements)>0) {
			foreach ($this->elements AS $assetGroup) {
				if (count($assetGroup)>0) {
					foreach ( $assetGroup AS $asset ) {
						$assets[] = $asset;
					}
				}
			}
		}

		// return all assets
		return $assets;
	}

	public function addAsset(CMXAsset $asset) {
		$this->elements[$asset->getAlbum()][] = $asset;
	}

	public function getAssetGroups() {
		foreach ($this->elements AS $group => $value) {
			$groups[] = $group;
		}
		return $groups;
	}

	public function getAssetsByGroup($group) {
		return $this->elements[$group];
	}

	public function asXML() {
		foreach ($this->elements AS $encoding) {
			$xml .= $encoding->asXML();
		}

		return $xml;
	}
}
