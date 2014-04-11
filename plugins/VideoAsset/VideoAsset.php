<?php

class VideoAsset extends AssetPlugin {

	// define plugin parameters
	protected $PLUGINID			= 'video-asset';
	protected $PLUGINSHORTNAME	= 'Video';
	protected $PLUGINNAME		= 'CME/X Video Asset Plugin';
	protected $PLUGINCOMMENT	= 'With this plugin, you are able to create Video assets in accordance with the CME/X Specification.';
	protected $PLUGINVIEW		= 'VideoAsset-View.php';

	// collection definition
	protected $COLLECTION		= 'videoCollection';
	protected $COLLECTIONTYPE	= 'VideoCollection';

	// field to retrieve artist information from
	protected $ARTISTFIELD		= 'artist';

	// since these attributes are optional, we don't want to output the attribute with an empty value that won't validate
	protected function getAttribute($attribute) {
		list($ns,$name) = explode(':',$attribute);

		// if this attribute was populated, we'll output it
		if (strlen($this->postdata[$name])>0) {
			return $attribute.'="'.$this->scrub($this->postdata[$name]).'" ';
		}

		// didn't get any data for this attribute, let's not output it
		return false;
	}

	// if we have thumbnail data, inject it
	protected function getThumbnail() {
		if ($this->postdata['thumbnail_selector'] == 'remote') {
			$thumbnail = $this->scrub($this->postdata['thumbnail']);
		} else {
			$thumbnail	= 'audio/'.$this->scrub($this->postdata['thumbnail'],true);
		}

		if(strlen($this->postdata['thumbnail'])>0) {
			$xml .= '<image>';
			$xml .= '<Image dc:title="thumbnail" rdf:parseType="Resource">';
			$xml .= '<displayArtist rdf:resource="contributors/'.$artist.'" />';
			$xml .= '<encoding>';
			$xml .= '<Encoding rdf:about="'.$thumbnail.'" dc:title="'.$this->scrub($this->postdata['title']).'" cmx:format="image/png"/>';
			$xml .= '</encoding>';
			$xml .= '</Image>';
			$xml .= '</image>';
		}
		return $xml;
	}

	function asXML() {
		// scrub the data we have for proper xml output
		$title		= $this->scrub($this->postdata['title']);
		$isrc		= $this->scrub($this->postdata['isrc']);
		$genre		= $this->scrub($this->postdata['genre']);
		$artist		= $this->scrub($this->postdata['artist'],true);

		if ($this->postdata['location'] == 'remote') {
			$path = $this->scrub($this->postdata['path']);
		} else {
			$path	= 'audio/'.$this->scrub($this->postdata['path'],true);
		}

		// format data for xml output
		$xml .= '<Video dc:title="'.$title.'" cmx:isrc="'.$isrc.'" cmx:genre="'.$genre.'" rdf:parseType="Resource">';
		$xml .= '<displayArtist rdf:resource="contributors/'.$artist.'" />';
		if(strlen($this->postdata['lyrics'])>0)
			$xml .= '<publishedLyrics rdf:resource="'.$this->getAssetURI($this->postdata['lyrics']).'" />';
		$xml .= '<encoding>';
		$xml .= '<Encoding rdf:about="'.$path.'" dc:title="'.$title.'" cmx:format="'.$this->postdata['format'].'" ';
						$this->getAttribute('cmx:cache').
						$this->getAttribute('cmx:requiresRegistration').
						$this->getAttribute('cmx:requiresProofOfPurchase').
						$this->getAttribute('cmxadmin:downloadsAllowed').
						$this->getAttribute('cmxadmin:externalMethod').
						$this->getAttribute('cmxadmin:externalURL').
						$this->getAttribute('cmxadmin:timeToLive').
					' >';
		$xml .= '</encoding>';

		// let's get the thumbnail object if we need to
		$xml .= $this->getThumbnail();

		// close the node
		$xml .= '</Video>';

		// return the XML
		return $xml;
	}

}
