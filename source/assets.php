<?php

include_once 'config/init.php';
include_once 'classes/PluginHelper.class.php';

if ($_REQUEST['pluginid']) {
	$ph = new PluginHelper();
	if ($plugin = $ph->getPluginById($_REQUEST['pluginid'])) {
		echo $plugin->getView();
		exit;
	} else {
		echo 'No plugin found [invalid id]';
	}
} else {
	echo 'No plugin found [none specified]';
}
