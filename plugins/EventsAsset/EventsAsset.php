<?php

class EventsAsset extends AssetPlugin {

	protected $PLUGINID			= 'events-asset';
	protected $PLUGINSHORTNAME	= 'Events';
	protected $PLUGINNAME		= 'CME/X Events Asset Plugin';
	protected $PLUGINCOMMENT	= 'With this plugin, you are able to create Events assets in accordance with the CME/X Specification.';
	protected $PLUGINVIEW		= 'EventsAsset-View.php';

	function asXML() {
		// scrub the data we have for proper xml output
		$title	= str_replace('&','&amp;',trim($this->postdata['title']));
		$feed	= str_replace('&','&amp;',trim($this->postdata['feed']));
		$format	= str_replace('&','&amp;',trim($this->postdata['format']));

		// format data for xml output
		$xml .= '<documentFeed>';
		$xml .= '<Feed rdf:about="'.$feed.'" cmx:feedFormat="'.$format.'" dc:title="'.$title.'"/>';

		// close the node
		$xml .= '</documentFeed>';

		// return the XML
		return $xml;
	}
}
