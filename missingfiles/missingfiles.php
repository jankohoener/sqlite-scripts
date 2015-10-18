<?php
/**
* Checks which files of a directory are missing in a SQLite3 database and returns a list of them.
*
* @arg dir The directory for which to check
* @arg dbfile The file containing the database
* @arg table The table name of the database
* @arg col The column containing the filenames
* @arg enckey The encryption key used for the database
* @returns A list of files missing from the database, or an empty list
*/
function missing_files_from_directory ($dir, $dbfile, $table, $col, $enckey = NULL) {
	$missing = array();
	$dirscan = scandir($dir, SCANDIR_SORT_ASCENDING /* default value */);
	if ($dirscan == false) {
		// Either $dir is not a directory or scandir had no success
		return $missing;
	}
	try {
		if (is_string($enckey)) {
			$db = new SQLite3($dbfile, SQLITE3_OPEN_READONLY, $enckey);
		}
		else {
			$db = new SQLite3($dbfile, SQLITE3_OPEN_READONLY);
		}
	}
	catch (Exception $e) {
		// Database could not be opened; return empty array
		return $missing;
	}
	foreach ($dirscan as $file) {
		if (is_dir($file) || is_link($file)) {// Filtering out directories (. and ..) and links {
			continue;
		}
		if ($db->querySingle("SELECT EXISTS(SELECT * FROM " . $table . " WHERE " . $col . " = '" . SQLite3::escapeString($file) . "');")) {
			// if an entry exists, returns TRUE, otherwise FALSE; invalid or failing queries return FALSE
			continue;
		}
		// entry does not exist; add to array
		$missing[] = $file;
	}
	$db->close();
	sort($missing, SORT_LOCALE_STRING | SORT_FLAG_CASE);
	return  $missing; // sort based on the locale, case-insensitive
}

/**
* Checks which files of a directory are missing in a SQLite3 database and prints them.
*
* @arg dir The directory for which to check
* @arg dbfile The file containing the database
* @arg table The table name of the database
* @arg col The column containing the filenames
* @arg enckey The encryption key used for the database
* @returns A list of files missing from the database, or an empty list
*/
function print_missing_from_directory ($dir, $dbfile, $table, $col, $enckey = NULL) {
	$missing = missing_files_from_directory($dir, $dbfile, $table, $col, $enckey);
	foreach ($missing as $file) {
		print "$file\n";
	}
}
