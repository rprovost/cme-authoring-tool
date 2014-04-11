<?php

include_once 'config/init.php';
include_once 'classes/PluginHelper.class.php';
include_once 'classes/Manifest.class.php';
include_once 'classes/EncodingList.class.php';
include_once 'classes/GroupedList.class.php';

// Initiate proper function
if (isset($_REQUEST['action'])) {
	if (function_exists($_REQUEST['action'])){
		$_REQUEST['action']();
	} else {
		die('<strong>Invalid method requested.</strong>');
	}
}

// Send them on their way
if (isset($_REQUEST['sendto'])) {
	header('location: ?do='.$_REQUEST['sendto']);
}

function home() {
	global $DB;

	// start a new bundle from a ddex file
	if (isset($_REQUEST['ddex'])) {
		$importer = ImporterFactory::LoadImporter('ddex');
		$importer->setSource(file_get_contents($_FILES['ddex']['tmp_name']));

		// transform the input
		$importer->transform();

		// instantiate the manifest object
		$manifest = new Manifest(MANIFESTID,PASSWORD,$importer->getCMX());

		// save the manifest
		$manifest->save();
	}

	// start a new bundle from a gras file
	if (isset($_REQUEST['gras'])) {
		$importer = ImporterFactory::LoadImporter('gras');
		$importer->setSource(file_get_contents($_FILES['gras']['tmp_name']));

		// transform the input
		$importer->transform();

		// instantiate the manifest object
		$manifest = new Manifest(MANIFESTID,PASSWORD,$importer->getCMX());

		// save the manifest
		$manifest->save();
	}

	// recall a previously started/created bundle
	if ( isset($_REQUEST['restore']) ) {
		$manifest = new Manifest($_REQUEST['bundleid'], $_REQUEST['bundlepwd']);

		if ($manifest->isLoaded()) {
			$_SESSION['bundler']['manifestid']	= $_REQUEST['bundleid'];
			$_SESSION['bundler']['password']	= $_REQUEST['bundlepwd'];
		} else {
			$_REQUEST['sendto'] = 'home';
		}
	}
}

function editbundle() {
	// load the manifest
	$manifest = new Manifest(MANIFESTID,PASSWORD);

	// update the manifest
	$manifest->cmx->setArtist($_REQUEST['cmx']['artist']);

	$manifest->cmx->setAttribute(CMX::TITLE,$_REQUEST['cmx']['title']);
	$manifest->cmx->setAttribute(CMX::RIGHTS,$_REQUEST['cmx']['rights']);
	$manifest->cmx->setAttribute(CMX::PARENTALWARNING,$_REQUEST['cmx']['explicit']);
	$manifest->cmx->setAttribute(CMX::GRID,$_REQUEST['cmx']['grid']);
	$manifest->cmx->setAttribute(CMX::GENRE,$_REQUEST['cmx']['genre']);
	$manifest->cmx->setAttribute(CMX::BASEURL,$_REQUEST['cmx']['base']);

	// save the manifest
	$manifest->save();
}

function editassets() {
	// load the manifest
	$manifest = new Manifest(MANIFESTID,PASSWORD);

	// remove old assets
	$manifest->cmx->clearAssets();

	// loop through all the plugins that were used
	foreach ($_REQUEST['plugin'] AS $plugin => $post) {
		$ph = new PluginHelper();
		if ($AssetPlugin = $ph->getPluginById($plugin)) {
			foreach ($post AS $uid => $data) {
				// set the posted data for the plugin to operate on
				$AssetPlugin->setPostedData($data);
				$AssetPlugin->setUID($uid);

				// add asset to the manifest
				$manifest->cmx->setAsset($AssetPlugin);
			}
		}
	}

	// let's get everything saved
	$manifest->save();

	// let's make sure this thing is actually valid before proceeding
	$valid = $manifest->cmx->isValid();

	// well, is it?
	if ( $valid !== true ) {
		echo '<p>There are some values that just don\'t seem right.  See the error log below so you can correct the problem.</p>';
		debug($valid);
		//header('content-type: text/xml');
		echo str_replace('<','&lt;',$manifest->cmx->asXML());
		exit;
	}

/***
 *
 * WE NEED TO GET THIS BUILT PROPERLY AND MAKE SURE RESUMING WORKS AGAIN - SHEESH
 * BUT FOR NOW, LET'S JUST GET THE MANIFEST OUTPUT CORRECT.
 *
 **/

	// define the bundle
	define('BUNDLE','bundle_'.MANIFESTID.'_'.PASSWORD.'.zip');
	define('BUNDLE_FOLDER',BUNDLE_ROOT.'/bundle_'.MANIFESTID);

	// Create folder, if necessary
	if (!is_dir(BUNDLE_FOLDER)) mkdir(BUNDLE_FOLDER);


/***
 * LET'S FORGET ABOUT FILE MANAGEMENT FOR NOW - WE'LL FIX CLEANUP IN THE NEXT ITERATION
 **
	// find all assets
	foreach ($_REQUEST['asset'] AS $asset) {
		$url		= BUNDLE_FOLDER.'/'.$asset['assettype'].'/'.$asset['url'];
		$lyrics		= BUNDLE_FOLDER.'/document/'.$asset['lyrics'];
		$preview	= BUNDLE_FOLDER.'/'.$asset['assettype'].'/'.$asset['preview'];

		if (file_exists($url) && is_file($url)) $assets[] = $url;
		if (file_exists($lyrics) && is_file($lyrics)) $assets[] = $lyrics;
		if (file_exists($preview) && is_file($preview)) $assets[] = $preview;
	}

	if (count($assets)>0) {
		// find all files
		$assettypes = array('audio','video','image','documents');
		foreach ($assettypes AS $assettype) {
			if (is_dir(BUNDLE_FOLDER.'/'.$assettype)) {
				$files[] = getDirectory(BUNDLE_FOLDER.'/'.$assettype);
			}
		}

		// remove files not found in assets
		foreach($files[0] AS $file) {
			if (!in_array($file,$assets)) {
				// remove file
				exec('rm ' . $file);
			}
		}
	}

	// NOT FILE MANAGEMENT, JUST INCLUDING IT SO I DON'T LOSE IT
	//debug($_REQUEST);
	//debug($manifest->cmx);
	header('content-type: text/xml');
	echo $manifest->cmx->asXML();
	exit;
***/

	/***
	 *	Let's get things all bundled up nice and nice
	 **/
	// make sure zipping is relative
	chdir(BUNDLE_ROOT);

	// clear out any previously compiled manifest
	exec('rm '.BUNDLE_FOLDER.'/manifest.*');

	// create manifest file in bundle
	$file = fopen(BUNDLE_FOLDER.'/manifest.rdf', 'x');
	fwrite($file,$manifest->cmx->asXML());

	// determine the name and path of the containing folder
	$foldername = urlencode($manifest->cmx->getFolder());
	$folderpath = BUNDLE_ROOT.'/'.$foldername;

	// all the fun file maniplulation stuff
	exec('rm ' . BUNDLE_ROOT.'/'.BUNDLE);					// remove the old bundle
	exec('cp -r '.DEFAULT_BUNDLE.'/* '.BUNDLE_FOLDER);		// create core bundle

	exec('mv '.BUNDLE_FOLDER.' '.$folderpath);				// rename the existing folder temporarily while we zip it up (so we have the proper folder name)
	exec('zip -r '.BUNDLE_ROOT.'/'.BUNDLE.' '.$foldername);	// zip up the bundle
	exec('mv '.$folderpath.' '.BUNDLE_FOLDER);				// revert the temporary name change

	// stick an xml file in the folder so it can be previewed since rdf isn't recognized as xml by standard browsers
	exec('rm -rf '.BUNDLE_FOLDER.'/manifest.xml');
	$file = fopen(BUNDLE_FOLDER.'/manifest.xml', 'x');
	fwrite($file,'<?xml version="1.0"?><?xml-stylesheet href="templates/default/default.xsl" type="text/xsl" ?>'.$manifest->cmx->asXML());
}
