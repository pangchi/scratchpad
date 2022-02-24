<?php
function listFiles() {
	return dropDots(scandir('files'));
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