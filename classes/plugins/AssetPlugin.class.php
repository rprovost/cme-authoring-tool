<?php

include_once 'classes/Plugin.class.php';
include_once 'classes/Interfaces.php';

abstract class AssetPlugin extends Plugin implements iAssetPlugin, XMLable  {

	protected $UID;
	protected $postdata = array();
	public $__assets; // I don't like this, but whatever

	function __construct() {
		if (!isset($this->UID)) $this->UID = uniqid();
	}

	function getPlugin() {}

	function setUID($uid) {
		$this->UID = $uid;
	}

	function getUID() {
		return $this->UID;
	}

	function getCollection() {
		return $this->COLLECTION;
	}

	function getArtist() {
		return $this->postdata[$this->ARTISTFIELD];
	}

	function getCollectionType() {
		return $this->COLLECTIONTYPE;
	}

	function setPostedData(Array $data) {
		$this->postdata = $data;
	}


	protected function scrub($string,$encode=false) {
		$string = trim($string);
		if($encode) {
			$string = urlencode($string);
			$string = str_replace('%2F','/',$string);
		} else {
			$string = str_replace('&','&amp;',$string);
		}
		return $string;
	}


	protected function getAssetURI($assetid) {
		foreach($this->__assets as $asset) {
			if ($asset->getUID() == $assetid) {
				return $asset->getURI();
			}
		}
		return false;
	}

	function getView() {
		$pathtoview	= self::PLUGINROOT.'/'.get_class($this).'/'.$this->PLUGINVIEW;
		$content	= file_get_contents($pathtoview);
		$content	.= $this->getResumeScript();
		$content	= $this->formatForm($content);
		$content	= '<div class="asset '.$this->getPluginID().' uid_'.$this->UID.'">'.$content.'</div>';

		return $content;
	}

	function getResumeScript() {
		$resume		= '<script language="javascript">';
		foreach ($this->postdata AS $name => $value){
			$resume .= '$(".editor").find(\':input[name="'.$name.'"]\').val("'.$value.'");'."\r\n";
		}
		$resume		.= '</script>';
		return $resume;
	}

	/***
	 *	This will work for our purposes now, but it will break plugin level PHP form arrays.
	 *	Point?  Don't use form arrays in your plugin!  ...at least for now.
	 **/
	function formatForm($content) {
		// update forms (will work for double quotes only - need to add single quote support)
		$pattern	= '/name="(\w+)*"/';
		$replacement= 'name="plugin['.$this->getPluginId().']['.$this->UID.'][$1]"';
		$output		= preg_replace($pattern, $replacement, $content);

		// update scripts (will work for double quotes only - need to add single quote support)
		$pattern	= '/\$\("\.(.*)"\)/';
		$replacement= '$(".uid_'.$this->UID.'").find(".$1")';
		$output		= preg_replace($pattern, $replacement, $output);

		// return the results
		return $output;
	}
}
