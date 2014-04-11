<?php

include_once 'classes/EncodingList.class.php';
include_once 'classes/GroupedList.class.php';
include_once 'classes/Doap.class.php';

class CMX {
	protected $doap;
	protected $release;
	protected $templates;
	protected $assets;
	protected $displayArtist;
	protected $output;

	const TITLE				= 'dc:title';
	const RIGHTS			= 'cmx:rights';
	const PARENTALWARNING	= 'cmx:parentalwarning';
	const GRID				= 'cmx:grid';
	const GENRE				= 'cmx:genre';
	const BASEURL			= 'xml:base';
	const TYPE				= 'rdf:type';
	const ABOUT				= 'rdf:about';

	const CMXGEM			= '/usr/local/bin/cmx';

	function __construct() {
		$this->templates = new EncodingList();

		// Setup appropriate namespaces
		$this->release['xmlns']					= 'http://cme-spec.org/0.9/terms#';
		$this->release['xmlns:cmx']				= 'http://cme-spec.org/0.9/terms#';
		$this->release['xmlns:cmxadmin']		= 'http://cme-spec.org/0.9/admin#';
		$this->release['xmlns:rdf']				= 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
		$this->release['xmlns:dc']				= 'http://purl.org/dc/terms/';
		$this->release['xmlns:doap']			= 'http://usefulinc.com/ns/doap#';

		// Sets the rdf type for our output.  This really shouldn't be set outside of here, although it can be overridden
		$this->setAttribute(self::TYPE,'http://cme-spec.org/0.9/terms#PrimaryRelease');
		$this->setAttribute(self::ABOUT,'manifest.rdf');

		// Don't know why this needs to be set, but...
		$this->release['cmxadmin:versionInfo']	=	'0.09.015';
	}

	public function setAttribute($attribute, $value) {
		$this->release[$attribute] = $value;
	}

	public function getAttribute($attribute) {
		return $this->release[$attribute];
	}

	public function addTemplate(CMXEncoding $template) {
		$this->templates->addEncoding($template);
	}

	public function setArtist($artist) {
		$this->displayArtist = $artist;
	}

	public function getArtist() {
		return $this->displayArtist;
	}

	public function clearAssets() {
		$this->assets = array();
	}

	public function setAsset(AssetPlugin $asset) {
		$asset->__assets = &$this->assets;
		$this->assets[] = $asset;
	}

	public function getAssets() {
		return $this->assets;
	}

	public function getFolder() {
		return $this->getArtist().'-'.$this->getAttribute(self::TITLE);
	}

	public function getAssetById($id) {
		if (isset($this->assets[$id])) {
			return $this->assets[$id];
		}
		return false;
	}

	/***
	 *	Create a list of available collections based upon assets currently set
	 **/
	protected function getCollections() {
		foreach ($this->assets AS $asset) {
			if (strlen($asset->getCollection())>0) {
				$collections[] = array(
						'name'=>$asset->getCollection(),
						'type'=>$asset->getCollectionType()
					);
			}
		}
		$array = array_uniquemulti($collections,'name');
		return $array;
	}

	/***
	 *	Get a list of assets by collection name
	 **/
	protected function getAssetsByCollection($collection) {
		foreach ($this->assets AS $asset) {
			if ($asset->getCollection() == $collection) {
				$assets[] = $asset;
			}
		}
		return $assets;
	}

	/***
	 *	Sets a DOAP object (Description Of A Project) to identify the authoring tool version information
	 **/
	protected function getDoap() {
		// add application versioning values
		$doap = new Doap();
		$doap->set('name',APP_NAME);
		$doap->set('url',APP_URL);
		$doap->set('created',APP_CREATED);
		$doap->set('version',APP_VERSION);
		$doap->set('modified',APP_MODIFIED);

		// return complete doap object as comforming xml
		return $doap->asXML();
	}

	public function isValid() {
		// Create unique name for the file
		$fid	= rand(10000,99999);
		$fname	= '/tmp/'.$fid.'.xml';
		$flog	= '/tmp/'.$fid.'.log';

		// Save the file to the 'tmp' folder for validation
		$file = fopen($fname, 'x');
		fwrite($file,$this->asXML());

		// let's validate
		$cmd = self::CMXGEM.' validate '.$fname.' --database /var/local/cmx.db --cert foo --privKey foo 2>&1';
		putenv('HOME=/');
		exec($cmd,$output);

		// let's store the output so we can display it if necessary
		$this->output = $output;

		// remove temporary file
		unlink($fname);

		// validation was successful - return true		
		if (substr($this->output[0], -2) == 'ok') return true;

		// something went wrong in validating - let's return the output so someone can do something with it
		return $output;
	}

	// WAY too substantial.  Need to break this up somehow in the future
	public function asXML() {
		// Since rdf:about is required, we need to set a default if one was not provided
		if (!array_key_exists(self::ABOUT,$this->release)) {
			$this->release[self::ABOUT] = 'manifest.rdf';
		}

		// RDF Root Node
		$xml = '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" schemaVersion="0.09.015" xsi:schemaLocation="http://cme-spec.org/0.9/terms#">';

		// Build Release parameters
		$xml .= '<Release';
		foreach ( $this->release AS $attribute => $value) {
			if ($attribute == self::BASEURL) $value = $value.urlencode($this->getFolder()).'/';
			if ($attribute == self::ABOUT) $value = urlencode($value);	// URL Encode rdf:about
			$xml .= ' '.$attribute.'="'.str_replace('"','&quot;',trim($value)).'"';
		}
		$xml .= '>';

		// include doap information
		$xml .= $this->getDoap();

		// populate templates
		$xml .= '<xslt><XSLT rdf:about="stylesheets/xslt" dc:title="Stylesheet">';
		$xml .= $this->templates->asXML();
		$xml .= '</XSLT></xslt>';

		// output the display artist - although it's not required by the spec, it has been requested by the client (very poor decision)
		if (strlen($this->getArtist())>0) {
			$xml .= '<displayArtist rdf:resource="contributors/'.urlencode($this->getArtist()).'"/>';
		}


		// going to need to account for an "&" in the artist title (e.g. Simon & Garfunkel)
		$displayArtists = array();
		foreach ($this->assets AS $asset) {
			if (strlen($asset->getArtist())>0 && !in_array($asset->getArtist(), $displayArtists)) {
				$displayArtists[] = $asset->getArtist();
				$xml .= '<releaseContributor>';
				$xml .= '<Contributor rdf:about="contributors/'.urlencode($asset->getArtist()).'" dc:title="'.$asset->getArtist().'" />';
				$xml .= '</releaseContributor>';
			}
		}

		// Add "release" contributor if it wasn't added by an asset
		if (!in_array($this->getArtist(), $displayArtists)) {
			$xml .= '<releaseContributor>';
			$xml .= '<Contributor rdf:about="contributors/'.urlencode($this->getArtist()).'" dc:title="'.$this->getArtist().'" />';
			$xml .= '</releaseContributor>';
		}

		// output all assets that are part of a collection (need to get collectionType working)
		foreach ($this->getCollections() AS $collection) {
			$xml .= '<'.$collection['name'].'>';
			$xml .= '<rdf:Seq rdf:type="http://cme-spec.org/0.9/terms#'.$collection['type'].'" rdf:about="collections/'.$collection['name'].'" dc:title="'.$collection['type'].'">';
			foreach ($this->getAssetsByCollection($collection['name']) AS $asset) {
				$xml .= '<rdf:li>';
				$xml .= $asset->asXML();
				$xml .= '</rdf:li>';
			}
			$xml .= '</rdf:Seq>';
			$xml .= '</'.$collection['name'].'>';
		}

		// output any assets that are not part of a collection
		foreach ($this->assets AS $asset) {
			if (!$asset->getCollection()) {
				$xml .= $asset->asXML();
			}
		}

		$xml .= '</Release>';
		$xml .= '</rdf:RDF>';

		return $xml;
	}

	public function __dumpassetList() {
		debug($this);
	}
}
