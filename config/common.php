<?php

// just a function for easily debugging objects
function debug($msg) {
	echo '<pre>'.print_r($msg,true).'</pre>';
}

// This will rebuild a serialized plugin
function unserialize_callback($plugin) {
	$ph = new PluginHelper();
	$plugin = $ph->getPluginById($plugin);
}

// generate a random password
// source: http://www.phpsnippets.info/generate-a-password-in-php
function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

// recursively lists all files in a directory and/or sub-directories
// modifed from source: http://stackoverflow.com/questions/4180282/how-do-i-sort-files-listed-by-php/4180312#4180312
function getDirectory ($path='.', $level=0, $maxdepth=0){ 
	// Directories to ignore when listing output. Many hosts will deny PHP access to the cgi-bin. 
	$ignore = array( 'cgi-bin', '.', '..', '.DS_Store' ); 

	// Open the directory to the handle $dh 
	$dh = @opendir( $path ); 

	// Loop through the directory 
	while( false !== ( $file = readdir( $dh ) ) ){ 

		// Check that this file is not to be ignored 
		if( !in_array( $file, $ignore ) ){ 

			// Its a directory, so we need to keep reading down... 
			if( is_dir("$path/$file") && ($maxdepth == 0 || $level < $maxdepth ) ){ 
				// Re-call this same function but on a new directory. this is what makes the function recursive. 
				$files = getDirectory( "$path/$file", ($level+1), $maxdepth);
				foreach($files AS $file) {
					$ary[]= $file;
				}
			} else {
				// Just print out the filename
				$dir = str_replace(RELEASE_FOLDER,'',$path);
				$ary[] = "$dir/$file";
			} 
		} 
	} 

	// Close the directory handle 
	closedir( $dh ); 
	return $ary;
}

// array_unique for multi-dimensional arrays
// source: http://www.php.net/manual/en/function.array-unique.php#62765
function array_uniquemulti($array, $index) {
	$array_count = count($array);
	$array_count_inner = count($array[$index]);
	for ($i=0; $i<$array_count_inner; $i++) {
		for ($j=$i+1; $j<$array_count_inner; $j++) {
			if ($array[$index][$i]==$array[$index][$j]) {
				for ($k=0; $k<$array_count; $k++) {
					unset($array[$k][$i]);
				} // end for    
			} // end if
		} // end for    
	} // end for
	return $array;
}
