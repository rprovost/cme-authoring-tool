<?php

class PluginHelper {

	protected $plugins = array();

	CONST PLUGIN_ROOT = '/plugins';

	function __construct() {
		$this->loadPlugins();
	}

	function loadPlugins() {
		$plugins = $this->loadDirectory(APP_ABSOLUTE_ROOT.'/classes/plugins');
		foreach ( $plugins AS $plugin ) {
			include_once APP_ABSOLUTE_ROOT.'/classes/plugins/'.$plugin;
		}	
	}

	function getPluginById($id) {
		if ($id) {
			$plugins = $this->getAllPlugins();
			if (count($plugins)>0) {
				foreach ($plugins AS $plugin) {
					if ($plugin->getPluginId() == $id) {
						return $plugin;
					}
				}
			}
		}
		return false;
	}

	function getPluginsByType($type) {
		if ($type) {
			$type = 'i'.$type.'Plugin';
			$plugins = $this->getAllPlugins();
			if (count($plugins)>0) {
				foreach ($plugins AS $plugin) {
					if (in_array($type,class_implements($plugin))) {
						$valid_plugins[] = $plugin;
					}
				}
			}
			return $valid_plugins;
		}
		return false;
	}

	function getAllPlugins() {
		if (count($this->plugins)==0) {
			$plugins = $this->loadDirectory(APP_ABSOLUTE_ROOT.'/'.self::PLUGIN_ROOT);
			if (count($plugins)>0) {
				foreach ( $plugins AS $plugin ) {
					if (@include_once APP_ABSOLUTE_ROOT.'/plugins/'.$plugin.'/'.$plugin.'.php') {
						if (class_exists($plugin)) {
							$po = new $plugin();
							if (in_array('iPlugin',class_implements($po))) {
								$this->plugins[] = $po;
							}
						}
					}
				}
			}
		}

		return $this->plugins;
	}


	protected function loadDirectory($dir) {
		$dir = scandir($dir);
		$directory = array_slice($dir,2);
		return $directory;
	}
}
