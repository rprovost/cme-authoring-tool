<?php
include_once 'config/init.php';

// define the bundle
define('BUNDLE_FILE','bundle_'.MANIFESTID.'_'.PASSWORD.'.zip');
define('BUNDLE_FOLDER','bundle_'.MANIFESTID);
define('BUNDLE',BUNDLE_ROOT.'/'.BUNDLE_FOLDER);

?>
<html>
<head>
	<title>CME Bundle Authoring Tool</title>
	<link href="template/master.css" rel="stylesheet" type="text/css" />
	<link href="template/build.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/master.js"></script>
	</script>
</head>

<body>

<div class="banner">CME Bundle Creator</div>

<div id="bundleid"><strong>BUNDLE ID:</strong> <?php echo MANIFESTID; ?> <div style="float: right"><strong>PASSWORD:</strong> <?php echo PASSWORD; ?></div></div>

<div id="method">
	<div id="copy">Your bundle is complete!</div>
	<a href="bundles/<?= BUNDLE_FOLDER ?>/manifest.xml" id="button" target="_blank">PREVIEW<br/>BUNDLE</a> <br/>
	<a href="bundles/<?= BUNDLE_FILE ?>" id="button">DOWNLOAD BUNDLE</a>
</div>

<div id="method">
	<div id="copy">Begin building a new bundle.</div>
	<a href="index.php" id="button">START NEW BUNDLE</a>
</div>

<div id="copyright">
	&copy;2009-<? echo date('Y'); ?> Connected Media Experience, Inc.
</div>

<div id="optimizedfor"></div>

</body>
</html>
