<?php

interface Transformable {
	public function setSource($string);
	public function transform();
}

interface CMXable extends Transformable {
    public function getCMX();
}

interface XMLable {
	public function asXML();
}

interface iPlugin {
	function getPluginId();
	function getPluginType();
	function getPluginName();
	function getPluginComment();
}

interface iImporterPlugin extends iPlugin {
	const PluginType = 'ImporterPlugin';

	function getPluginHandler();
	function getPluginFields();
}

interface iAssetPlugin extends iPlugin {
	const PluginType = 'AssetPlugin';

	function getView();
	function getCollection();
	function setPostedData(Array $data);
}
