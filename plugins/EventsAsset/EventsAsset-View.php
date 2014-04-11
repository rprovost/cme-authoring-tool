<!-- Asset specific Javascript -->
<script>
	// Make sure things are ready for this kind of code
	$(document).ready(function() {
		// Display security preferences
		$(".security").hide();

		// Handle field specific display updates
		$(".title").keyup(function(){
			$(".artisthead").text($(this).val());
		});
	});
</script>

<!-- Accordion Header -->
<h3 class="toggler">
	<div class="truncated artisthead">New Events Asset</div>
	<div class="remove"><a href="#" onclick="removeAsset($(this));"><img src="images/close_small.png"></a></div>
	<input style="display: none;" type="text" name="assetgroup" value="news" />
	<input style="display: none;" type="text" name="assetformat" value="document" />
	<input style="display: none;" type="text" name="assettype" value="document" />
</h3>

<!-- Asset Content -->
<div id="editor" class="editor">
	<fieldset>
		<table class="input-form">
			<tr>
				<th>
					<label for="user_name">Title</label>
				</th>
				<td class="col-field">
					<input class="text title" name="title" value="" size="30" tabindex="2" type="text" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="user_name">Format</label>
				</th>
				<td class="col-field">
					<select class="dropdown" id="format" name="format" tabindex="5" type="text">
						<option disabled/>
						<option value="json">JSON</option>
						<option value="rss">RSS</option>
						<option value="atom">ATOM</option>
						<option value="xml">XML</option>
					</select>
				</td>
			</tr>
			<tr class="feed">
				<th>
					<label for="feed">Feed</label>
				</th>
				<td class="col-field">
					<input class="text" name="feed" placeholder="http://" size="30" tabindex="6" type="url" autocomplete="off" />
					<div class="processing"><img src="images/processing.gif" alt="Uploading file..."/></div>
					<div class="complete"><img src="images/complete.png" alt="File successfully uploaded"/></div>
					<div class="error"><img src="images/error.png" alt="Unable to upload file"/></div>
				</td>
			</tr>
			<tr>
				<th></th>
				<td class="col-field">
					<div class="security">Protected By:
						<input type="checkbox" name="requiresRegistration" id="requiresRegistration" tabindex="7" /> <label for="requiresRegistration">Registration</label>
						<input type="checkbox" name="requiresProofOfPurchase" id="requiresProofOfPurchase" tabindex="8" /> <label for="requiresProofOfPurchase">Proof-of-Purchase</label>

						<a href="#" class="details_toggle expander"><img src="images/expand.png" alt="Advanced Options" class="expander_icon"></a>

						<!-- Security Details -->
						<div class="details">
							<hr class="hr" />
							<div style="text-align: center; font-weight: bold;">Advanced Options</div>
							<hr class="hr" />

							<table>
								<tr>
									<th>Caching</th>
									<td>
										<select name="asset_location" class="selector asset">
											<option disabled="disabled"></option>
											<option value="cache">Cacheable</option>
											<option value="network">Online Only</option>
										</select>
									</td>
								</tr>
								<tr>
									<th>Asset Handler</th>
									<td>
										<select class="dropdown accessselector" id="handler" name="admin][externalMethod" tabindex="9" autocomplete="off" style="">
											<option disabled/>
											<option value="proxy">Proxy</option>
											<option value="temporaryRedirect">Redirect</option>
										</select>
									</td>
								</tr>
								<tr>
									<th>Proxy URL</th>
									<td><input name="admin][externalURL" value="{externalURL}" size="25" tabindex="10" type="url" autocomplete="off" placeholder="http://" /></td>
								</tr>
								<tr>
									<th>Downloads Allowed</th>
									<td><input name="admin][downloadsAllowed" value="{downloadsAllowed}" size="5" tabindex="11" type="number" min="0" autocomplete="off" /></td>
								</tr>
								<tr>
									<th>Content Expiration</th>
									<td><input name="admin][timeToLive" value="{timeToLive}" size="5" tabindex="12" type="number" min="1" autocomplete="off" /> days</td>
								</tr>
							</table>
						</div>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
