<?php
include_once 'dirops.php';

if (isset($_REQUEST['value'])) {
	$value = $_REQUEST['value'];
	writeFile($value);
}

function writeFile($value) {
	$filename = "scratch.json";
	$file = fopen($filename, "w");
	$data = [];
	$data['time'] = time();
	$data['value'] = $value;
	if (isset($_REQUEST['clear'])) 
	{
		if ($_REQUEST['clear'] != "false") {
			deleteAllFiles();
		}
	}
	$data['files'] = listFiles();
	$json = json_encode($data);

	fwrite($file, $json);
	fclose($file);
}
?>