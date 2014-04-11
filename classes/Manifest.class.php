<?php

include_once 'classes/CMX.class.php';

class Manifest {
	protected $manifestid;
	protected $password;
	protected $loaded = false;
	public $cmx;

	public function __construct($manifestid, $password, CMX $manifest=NULL) {
		$this->manifestid	= $manifestid;
		$this->password		= $password;

		$this->cmx = $manifest ? $manifest : $this->loadManifest();
	}

	public function isLoaded() {
		return $this->loaded;
	}

	public function save() {
		if ($this->isLoaded()) {
			$query = 'UPDATE '.DB.' SET manifest = "'.mysql_real_escape_string(serialize($this->cmx)).'", lastmodified=now() WHERE manifestid = '.$this->manifestid.' AND password = "'.$this->password.'"';
		} else {
			$query = 'INSERT INTO '.DB.' (manifestid,password,manifest,lastmodified,created) VALUES ('.$this->manifestid.',"'.$this->password.'","'.mysql_real_escape_string(serialize($this->cmx)).'",now(),now())';
		}
		mysql_query($query);
	}

	protected function loadManifest() {
		$query	 = 'SELECT manifest FROM '.DB.' WHERE manifestid = '.$this->manifestid.' AND password = "'.$this->password.'"';
		$results = mysql_query($query);

		if (@mysql_num_rows($results)>0) {
			$this->loaded = true;

			$result	 = mysql_fetch_array($results);
			return unserialize($result['manifest']);
		} else {
			return new CMX();
		}
	}
}
