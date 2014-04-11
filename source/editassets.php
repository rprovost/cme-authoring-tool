<?php
include_once 'config/init.php';
include_once 'classes/PluginHelper.class.php';
include_once 'classes/Manifest.class.php';
include_once 'classes/EncodingList.class.php';
include_once 'classes/GroupedList.class.php';

// load manifest
$manifest	= new Manifest(MANIFESTID,PASSWORD);
$assets		= $manifest->cmx->getAssets();
$ph			= new PluginHelper();
?><html>
<head>
	<title>CME Bundle Authoring Tool</title>
	<link href="template/master.css" rel="stylesheet" type="text/css" />
	<link href="template/editassets.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.ui.core.js"></script>
	<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="js/jquery.ui.mouse.js"></script>
	<script type="text/javascript" src="js/jquery.ui.sortable.js"></script>
	<script type="text/javascript" src="js/jquery.ui.accordion.js"></script>
	<script type="text/javascript" src="js/ajaxupload.js"></script>
	<script type="text/javascript" src="js/regexHandlers.js"></script>
	<script type="text/javascript" src="js/master.js"></script>
	<script type="text/javascript" src="js/editassets.js"></script>
	<script type="text/javascript">
		var bundleid	= "<?= MANIFESTID; ?>";
		var password	= "<?= PASSWORD; ?>";
	</script>
</head>

<body>

<div class="banner">CME Bundle Creator</div>

<div id="bundleid">
	<span><strong>BUNDLE ID:</strong></span> <span id="bundleIDVal"><?php echo MANIFESTID; ?></span>
	<div style="float: right">
		<span><strong>PASSWORD:</strong></span> <span id="bundlePWDVal"><?php echo PASSWORD; ?></span>
	</div>
</div>

<div class="accordion">
	<form id="editassets" name="editassets" action="?do=process" method="post">
		<div class="assets" id="assets">
			<?php if (count($assets)>0): ?>
				<? foreach ($assets AS $asset): ?>
					<?= $asset->getView(); ?>
				<? endforeach; ?>
				<script>
					rebuildAccordion();
					$(".assets").accordion("activate");
					$(".artist").trigger("keyup");
				</script>
			<?php else: ?>
				<h3 class="emptyaccordion">Add an asset below</h3>
			<?php endif; ?>
		</div>
		<input type="hidden" name="action" value="editassets"/>
		<input type="hidden" name="sendto" value="build"/>
	</form>
</div>

<div id="controls">
	<div class="selector plugins">
		<? FOREACH ($ph->getPluginsByType('Asset') AS $plugin): ?>
			<div class="selector plugin"><a href="#" onClick="newAsset('<?= $plugin->getPluginId(); ?>');"><?= $plugin->getPluginShortName(); ?></a></div>
		<? ENDFOREACH; ?>
	</div>
	<a class="button selector" href="#">ADD ASSET</a>
	<a class="button restart" href="?do=home" onclick="return confirm('Are you sure you want to start over?\n\rEverything you\'ve entered will be lost!\n');">START OVER</a>
	<a class="button build" href="#">BUILD BUNDLE</a>
</div>

<div id="spacer" style="clear: both; padding: 1px;"></div>

<div id="copyright">
	&copy;2009-<? echo date('Y'); ?> Connected Media Experience, Inc.
</div>

<div id="optimizedfor" />

</body>
</html>
