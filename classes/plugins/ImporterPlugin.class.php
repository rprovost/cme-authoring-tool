<?

include_once 'classes/Plugin.class.php';
include_once 'classes/Interfaces.php';
include_once 'classes/CMX.class.php';

abstract class ImporterPlugin extends Plugin implements CMXable, iImporterPlugin {

	protected $source;
	protected $cmx;

	public function __construct() {
		$this->cmx = new CMX();
	}

	public function setSource($input) {
		$this->source = $input;
	}

	public function getCMX() {
		return $this->cmx;
	}

	public function getPluginHandler() {
		return $this->PLUGINHANDLER;
	}

	public function getPluginFields() {
		return false;
	}

	function getPlugin($sourceFormat) {
		$sourceFormat = ucfirst($sourceFormat).'Importer';

		if (file_exists('classes/importers/'.$sourceFormat.'.class.php')) {
			if ( @include_once 'classes/importers/'.$sourceFormat.'.class.php' ) {
				$importer = new $sourceFormat;
				if (get_class($importer) == $sourceFormat) {
					return $importer;
				} else {
					throw new Exception('Invalid Importer');
				}
			} else {
				throw new Exception('Unable to load Importer');
			}
		} else {
			throw new Exception('Importer not found');
		}
	}
}
