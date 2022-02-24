<?php
function listFiles() {
	return dropDots(scandir('files'));
}

function deleteAllFiles() {
	$files = listFiles();
	foreach ($files as $file) {
		unlink('files/'. $file);
	}
}

function dropDots($files) {
	$fileList = [];
	foreach ($files as $file) {
		if (($file != ".") AND ($file != "..")) {
			array_push($fileList, $file);
		}
	}
	return $fileList;
}

?>