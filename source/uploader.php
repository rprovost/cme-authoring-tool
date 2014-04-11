<?php

// Get our defaults
include_once 'config/init.php';

// Create bundle constants
define('BUNDLE_FOLDER',BUNDLE_ROOT.'/bundle_'.MANIFESTID);
define('ASSET_FOLDER',BUNDLE_FOLDER.'/'.$_REQUEST['type']);

// Create folders, if necessary
if (!is_dir(BUNDLE_FOLDER)) mkdir(BUNDLE_FOLDER);
if (!is_dir(ASSET_FOLDER)) mkdir(ASSET_FOLDER);

// Move the file to the appropriate file
if (move_uploaded_file($_FILES['userfile']['tmp_name'], ASSET_FOLDER.'/'.$_FILES['userfile']['name'])) {
	echo "success";
} else {
	echo "error";
}
