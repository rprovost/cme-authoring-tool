<?php

class DDEXImporter extends ImporterPlugin {

	// Set Plugin Required Constants
	protected $PLUGINID			= 'ddex';
	protected $PLUGINNAME		= 'DDEX-ERN Message';
	protected $PLUGINCOMMENT	= 'You can get the DDEX specification at <a href="http://www.ddex.net/" target="_blank">www.ddex.net</a>';
	protected $PLUGINHANDLER	= 1;  // 1 = upload ; 2 = form

	protected $album;
	protected $products;

	function transform() {
		$source = new SimpleXMLElement($this->source);

		// Get Bundle Information
		$release = $source->xpath('//ReleaseList/Release');
		$release = $release[0];

		$this->cmx->setAttribute(CMX::TITLE,(String) $release->ReferenceTitle->TitleText);
		$this->cmx->setAttribute(CMX::GRID,(String) $release->ReleaseId->GRid);

		// Get Assets
		$assets = $source->xpath('//ReleaseList/Release/ReleaseResourceReferenceList');
		$assets = $assets[0];

		foreach ($assets AS $asset) {
			$asset = (String) $asset;
			$asset = $source->xpath('//SoundRecording[ResourceReference="'.$asset.'"]');
			$asset = $asset[0];

			$details = $asset->xpath('SoundRecordingDetailsByTerritory[TerritoryCode = ("US" or "Worldwide")]');
			$details = $details[0];

			$ca = new CMXAsset();
			$ca->setGroup(CMXAsset::AUDIO);
			$ca->set(CMXAsset::ISRC,(String) $asset->SoundRecordingId->ISRC);
			$ca->set(CMXAsset::DISPLAYARTIST,(String) $details->DisplayArtist->PartyName->FullName);
			$ca->set(CMXAsset::TITLE,(String) $asset->ReferenceTitle->TitleText);
			$ca->set(CMXAsset::GENRE,(String) $details->Genre->GenreText);

			$this->cmx->addAsset($ca);
		}
	}
}
