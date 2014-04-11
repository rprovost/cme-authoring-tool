<?php

include_once 'config/init.php';
include_once 'classes/Manifest.class.php';
include_once 'classes/EncodingList.class.php';
include_once 'classes/GroupedList.class.php';

// load manifest
$manifest = new Manifest(MANIFESTID,PASSWORD);

// get manifest parameters
if ($manifest->isLoaded()) {
	$page['title']		= $manifest->cmx->getAttribute(CMX::TITLE);
	$page['rights']		= $manifest->cmx->getAttribute(CMX::RIGHTS);
	$page['warning']	= $manifest->cmx->getAttribute(CMX::PARENTALWARNING);
	$page['grid']		= $manifest->cmx->getAttribute(CMX::GRID);
	$page['genre']		= $manifest->cmx->getAttribute(CMX::GENRE);
	$page['base']		= $manifest->cmx->getAttribute(CMX::BASEURL);
	$page['artist']		= $manifest->cmx->getArtist();
} else {
	$page['title']		= 'Title';
	$page['artist']		= 'Artist';
	$page['base']		= 'http://cme.pushentertainment.com/releases/';
}
?><html>
<head>
	<title>CME Bundle Authoring Tool</title>
	<link href="template/master.css" rel="stylesheet" type="text/css" />
	<link href="template/editbundle.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
	<script type="text/javascript" src="js/jquery.ui.core.js"></script>
	<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="js/jquery.ui.accordion.js"></script>
	<script type="text/javascript" src="js/master.js"></script>
	<script type="text/javascript" src="js/editbundle.js"></script>
</head>

<body>

<div class="banner">CME Bundle Creator</div>

<div id="bundleid"><strong>BUNDLE ID:</strong> <?php echo MANIFESTID; ?> <div style="float: right"><strong>PASSWORD:</strong> <?php echo PASSWORD; ?></div></div>

<div id="accordion">
	<form id="editbundle" name="editbundle" action="?do=process" method="post">
		<div class="bundle" id="bundle">
			<h3 class="toggler">
				<div class="truncated artisthead">New Bundle</div>
				<div class="truncated titlehead"></div>
			</h3>
			<div class="element">
				<div id="editor" class="editor">
					<fieldset>
						<table class="input-form">
							<tr>
								<th>
									<label for="user_name">Artist:</label>
								</th>
								<td class="col-field">
									<input class="text artist" name="cmx[artist]" value="<?php echo @$page['artist']; ?>" size="30" tabindex="1" type="text" />
								</td>
							</tr>
							<tr>
								<th>
									<label for="user_name">Title:</label>
								</th>
								<td class="col-field">
									<input class="text title" name="cmx[title]" value="<?php echo @$page['title']; ?>" size="30" tabindex="1" type="text" />
								</td>
							</tr>
							<tr>
								<th>
									<label for="user_name">GRid:</label>
								</th>
								<td class="col-field">
									<input class="text grid" name="cmx[grid]" value="<?php echo @$page['grid']; ?>" size="30" tabindex="1" type="text" />
								</td>
							</tr>
							<tr>
								<th>
									<label for="user_name">Rights Holder:</label>
								</th>
								<td class="col-field">
									<input class="text" id="rights" name="cmx[rights]" value="<?php echo @$page['rights']; ?>" size="30" tabindex="1" type="text" />
								</td>
							</tr>
							<tr>
								<th>
									<label for="user_name">Genre:</label>
								</th>
								<td class="col-field">
									<input class="text" id="genre" name="cmx[genre]" value="<?php echo @$page['genre']; ?>" size="30" tabindex="1" type="text" />
								</td>
							</tr>
							<tr>
								<th>
									<label for="user_name">Warning:</label>
								</th>
								<td class="col-field">
									<select class="dropdown" id="explicit" name="cmx[explicit]" tabindex="1" type="text">
										<option disabled></option>
										<option value="NotExplicit" <?php if ( $page['warning'] == 'NotExplicit' ) echo 'selected="selected"';?>>Not Explicit</option>
										<option value="Explicit" <?php if ( $page['warning'] == 'Explicit' ) echo 'selected="selected"';?>>Explicit</option>
										<option value="ExplicitContentEdited" <?php if ( $page['warning'] == 'ExplicitContentEdited' ) echo 'selected="selected"';?>>Explicit (Content Edited)</option>
										<option value="NoAdviceAvailable" <?php if ( $page['warning'] == 'NoAdviceAvailable' ) echo 'selected="selected"';?>>No Advice Available</option>
										<option value="Unknown" <?php if ( $page['warning'] == 'Unknown' ) echo 'selected="selected"';?>>Unknown</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>
									<label for="user_name">Base URL</label>
								</th>
								<td class="col-field">
									<input class="text base" name="cmx[base]" value="<?php echo $page['base']; ?>" size="30" tabindex="1" type="text" />
								</td>
							</tr>
						</table>
					</fieldset>
				</div>
			</div>
		</div>
		<input type="hidden" name="action" value="editbundle"/>
		<input type="hidden" name="sendto" value="editassets"/>
	</form>
</div>

<div id="controls">
	<a class="cancel button" id="button1" href="?do=home" onclick="return confirm('Are you sure you want to start over?\n\rEverything you\'ve entered will be lost!\n'); document.location='index.php'">START OVER</a>
	<a class="continue button" id="continue" href="#">CONTINUE</a>
</div>

<div id="spacer" style="clear: both; padding: 1px;"></div>

<div id="copyright">
	&copy;2009-<? echo date('Y'); ?> Connected Media Experience, Inc.
</div>

<div id="optimizedfor"></div>

</body>
</html>
