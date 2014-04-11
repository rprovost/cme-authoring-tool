<!-- Asset specific Javascript -->
<script>
	// Make sure things are ready for this kind of code
	$(document).ready(function() {
		$(".selector.lyrics").change(function() {
			switch($(this).val()) {
				case "bundle":
					$(".lyrics .complete").hide();
					$(".lyrics .error").hide();
					$(".lyrics .file").fadeIn("fast");
					$(".lyrics .url").hide();
					$(".lyrics .security").slideDown("fast");
					break;
	
				case "remote":
					$(".lyrics .complete").hide();
					$(".lyrics .error").hide();
					$(".lyrics .file").hide();
					$(".lyrics .url").fadeIn("fast");
					$(".lyrics .security").slideDown("fast");
					break;
			}
		});
	
		$(".selector.thumbnail").change(function() {
			switch($(this).val()) {
				case "bundle":
					$(".thumbnail .complete").hide();
					$(".thumbnail .error").hide();
					$(".thumbnail .file").fadeIn("fast");
					$(".thumbnail .url").hide();
					break;
	
				case "remote":
					$(".thumbnail .complete").hide();
					$(".thumbnail .error").hide();
					$(".thumbnail .file").hide();
					$(".thumbnail .url").fadeIn("fast");
					break;
			}
		});
	
		$(".details_toggle").click(function(){
			if ( $(".details").is(":visible") ) {
				$(".details").slideUp("fast");
				$(".expander_icon").attr("src","images/expand.png");
			} else {
				$(".details").slideDown("fast");
				$(".expander_icon").attr("src","images/collapse.png");
			}
		});
	
		// upload file
		$(".upload").each(function() {
			var button = $(this);
	
			// Source code: https://github.com/valums/ajax-upload
			var test = new AjaxUpload(this, {
				action: '/authoring/?do=uploader&type=audio',
				onSubmit: function(file, ext){
					//uploading++;
					// check for valid file extension - doesn't seem to work, currently
					if (!(ext && /^(mp3)$/.test(ext))) { $(button).parent().next('.error').fadeIn('fast'); }

					// set form value	
					if ($(button).hasClass("asset")) $(button).parent().nextAll(':input[name="location"]').val(file);
					if ($(button).hasClass("thumbnail")) $(button).parent().nextAll(':input[name="thumbnail"]').val(file);

					// display processing notification
					$(button).parent().nextAll('.processing').fadeIn('fast');
				},
				onComplete: function(file, response){
					$(button).parent().nextAll('.processing').hide();
	
					if(response === "success"){
						$(button).parent().nextAll('.complete').fadeIn('fast');
					} else {
						$(button).parent().nextAll('.error').fadeIn('fast');
					}
					//uploading--;
				}
			})
		});
	
		// Display the "remove" icon for an asset
		$(".security").live("mouseenter",function(){$(this).find(".expander").fadeIn("fast");});
	
		// Hide the "remove" icon for an asset
		$(".security").live("mouseleave",function(){
			if (!$(this).find(".details").is(":visible")) {
				$(this).find(".expander").fadeOut("fast");
			}
		});
	
		// Handle field specific display updates
		$(".artist").keyup(function(){
			$(".artisthead").text($(this).val());
		});
	
		$(".title").keyup(function(){
			$(".titlehead").text($(this).val());
		});
	
		$(".lyrics.url").keyup(function(){
			$(".editor").find(':input[name="location"]').val($(this).val());
		});
	});
</script>

<!-- Accordion Header -->
<h3 class="toggler">
	<div class="truncated artisthead">New Lyrics Asset</div>
	<div class="truncated titlehead"></div>
	<div class="remove"><a href="#" onclick="removeAsset($(this));"><img src="images/close_small.png"></a></div>
</h3>

<!-- Accordion Content => Asset Editor -->
<div class="editor">
	<fieldset>
		<table class="input-form">
			<tr>
				<th>
					<label for="artist">Artist</label>
				</th>
				<td class="col-field">
					<input class="text artist" name="artist" placeholder="Artist Name" value="" size="30" tabindex="1" type="text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="title">Title</label>
				</th>
				<td class="col-field">
					<input class="text title" name="title" placeholder="Title" value="" size="30" tabindex="2" type="text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="user_name">Format</label>
				</th>
				<td class="col-field">
					<select class="dropdown" id="format" name="format" tabindex="5" type="text">
						<option disabled/>
						<option value="text/plain">Text</option>
						<option value="application/pdf">PDF</option>
					</select>
				</td>
			</tr>
			<tr class="thumbnail">
				<th>
					<label for="thumbnail">Thumbnail</label>
				</th>
				<td class="col-field">
					<table class="sub-table">
						<td class="sub-table-header">
							<select class="selector thumbnail" name="thumbnail_location" style="width: 100px;">
								<option disabled="disabled"></option>
								<option value="bundle">Bundle</option>
								<option value="remote">Remote</option>
							</select>
						</td>
						<td class="sub-table-col-field">
							<div class="url">
								<input class="form text url remote" name="thumbnail" type="url" tabindex="6" placeholder="http://" size="30" />
							</div>
							<div class="file">
								<input class="upload file" name="thumbnail" type="button" value="Choose File"/>
							</div>
							<div class="processing"><img src="images/processing.gif" alt="Uploading file..."/></div>
							<div class="complete"><img src="images/complete.png" alt="File successfully uploaded"/></div>
							<div class="error"><img src="images/error.png" alt="Unable to upload file"/></div>
						</td>
					</table>
				</td>
			</tr>
			<tr class="lyrics">
				<th>
					<label for="lyrics_location">Lyrics</label>
				</th>
				<td class="col-field">
					<table class="sub-table">
						<td class="sub-table-header">
							<select class="selector lyrics" name="location_selector" style="width: 100px;">
								<option disabled="disabled"></option>
								<option value="bundle">Bundle</option>
								<option value="remote">Remote</option>
							</select>
						</td>
						<td class="sub-table-col-field">
							<span class="url"><input class="form text url remote lyrics" name="url" type="url" placeholder="http://" size="30" tabindex="6" /></span>
							<span class="file"><input class="upload file lyrics" name="file" type="button" value="Choose File"/></span>
							<span class="processing"><img src="images/processing.gif" alt="Uploading file..."/></span>
							<span class="complete"><img src="images/complete.png" alt="File successfully uploaded"/></span>
							<span class="error"><img src="images/error.png" alt="Unable to upload file"/></span>
							<input class="location" type="hidden" name="location"/>
						</td>
					</table>
				</td>
			</tr>
			<tr class="lyrics">
				<th></th>
				<td class="col-field">
					<div class="security">Protected By:
						<input type="checkbox" name="requiresRegistration" value="true" tabindex="7" /> <label for="requiresRegistration">Registration</label>
						<input type="checkbox" name="requiresProofOfPurchase" value="true" tabindex="8" /> <label for="requiresProofOfPurchase">Proof-of-Purchase</label>

						<a href="#" class="details_toggle expander"><img src="images/expand.png" alt="Advanced Options" class="expander_icon"></a>

						<!-- Security Details -->
						<div id="asset_{_uid_}_details" class="details">
							<hr class="hr" />
							<div style="text-align: center; font-weight: bold;">Advanced Options</div>
							<hr class="hr" />

							<table>
								<tr>
									<th>Caching</th>
									<td>
										<select name="cache" class="selector asset">
											<option value=""></option>
											<option value="cache">Cacheable</option>
											<option value="network">Online Only</option>
										</select>
									</td>
								</tr>
								<tr>
									<th>Asset Handler</th>
									<td>
										<select class="dropdown accessselector" id="handler" name="externalMethod" tabindex="9" autocomplete="off" style="">
											<option value=""></option>
											<option value="proxy">Proxy</option>
											<option value="temporaryRedirect">Redirect</option>
										</select>
									</td>
								</tr>
								<tr>
									<th>Proxy URL</th>
									<td><input name="externalURL" size="25" tabindex="10" type="url" autocomplete="off" placeholder="http://" /></td>
								</tr>
								<tr>
									<th>Downloads Allowed</th>
									<td><input name="downloadsAllowed" size="5" tabindex="11" type="number" min="0" autocomplete="off" /></td>
								</tr>
								<tr>
									<th>Content Expiration</th>
									<td><input type="hidden" name="timeToLive"/><input class="timeToLiveDisplay" name="timeToLiveDisplay" size="5" tabindex="12" type="number" min="1" autocomplete="off" /> days</td>
								</tr>
							</table>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
