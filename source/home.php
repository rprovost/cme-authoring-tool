<?php
include_once 'config/init.php';
include_once 'classes/PluginHelper.class.php';

unset($_SESSION['bundler']);

$ph = new PluginHelper();
$ph->getAllPlugins();
$specific_importers = $ph->getPluginsByType('Importer');
?>
<html>
<head>
	<title>CME Bundle Authoring Tool</title>
	<link href="template/master.css" rel="stylesheet" type="text/css" />
	<link href="template/home.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.ui.core.js"></script>
	<script type="text/javascript" src="js/master.js"></script>
	<script type="text/javascript" src="js/home.js"></script>
</head>

<body>

<div class="banner">CME Bundle Creator</div>

<div id="method">
	<div id="copy"><strong>Start From:</strong> Scratch</div>
	<a href="?do=process&action=home&sendto=editbundle" id="button">CONTINUE</a>
</div>

<? FOREACH($specific_importers AS $importer): ?>
		<div id="method">
			<div id="copy"><strong>Start From:</strong> <?= $importer->getPluginName() ?></div>
			<form id="<?= $importer->getPluginId() ?>" method="post" action="?do=process&action=home&sendto=editbundle" enctype="multipart/form-data">
				<? IF ($importer->getPluginHandler() == 1): ?>
					<div id="uploader">
						<input id="<?= $importer->getPluginId() ?>_upload" class="uploader" name="<?= $importer->getPluginId() ?>" type="file" onChange="document.getElementById('<?= $importer->getPluginId() ?>').submit();" />
						<div id="button" onClick="document.getElementById('<?= $importer->getPluginId() ?>_upload').select();">UPLOAD</div>
					</div>
				<? ELSEIF ($importer->getPluginHandler() == 2): ?>
					<!-- -->
				<? ENDIF; ?>
				<input type="hidden" name="<?= $importer->getPluginId() ?>" value="true"/>
			</form>
			<div id="comment"><?= $importer->getPluginComment() ?></div>
		</div>
<? ENDFOREACH; ?>

<div id="method">
	<div id="copy"><strong>Start From:</strong> Existing Bundle</div>
	<form id="resume" method="post" action="?do=process&action=home&sendto=editbundle">
		<div id="text"><input type="text" id="textinput" class="resume bundleid" name="bundleid" value="bundle id" /></div>
		<div id="text"><input type="text" id="textinput" class="resume bundlepwd" name="bundlepwd" value="password" /></div>
		<input type="hidden" name="restore" value="true"/>
	</form>
	<a href="#" id="button" onClick="document.getElementById('resume').submit();">RESUME</a>
</div>

<div id="copyright">
	&copy;2009-<? echo date('Y'); ?> Connected Media Experience, Inc.
</div>

<div id="optimizedfor">Optimized for <a href="http://www.google.com/chrome" target="_blank">Chrome</a></div>

<?
if (isset($_REQUEST['msg'])) {
	switch($_REQUEST['msg']) {
		case 100:
			//echo '<script>alert("Your session expired.");</script>';
			break;
	}
}
?>

</body>
</html>
