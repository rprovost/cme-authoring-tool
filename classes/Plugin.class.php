<?php

abstract class Plugin {

	CONST PLUGINROOT = 'plugins';

	function getPluginId() {
		return $this->PLUGINID;
	}

	function getPluginType() {
		return self::PluginType;
	}

	function getPluginName() {
		return $this->PLUGINNAME;
	}

	function getPluginShortName() {
		return $this->PLUGINSHORTNAME;
	}

	function getPluginComment() {
		return $this->PLUGINCOMMENT;
	}
}
