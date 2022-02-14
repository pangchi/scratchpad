<?php
$filename = "scratch.json";
$value = "";
if (file_exists($filename)){
	
	$file = fopen($filename, "r");

	$json = fgets($file);
	//$data = json_decode($json, true);
	//$value = $data['value'];
	fclose($file);
	
	/* decrepited
	if (time() - intval($data['time']) > 60) {
		$value = "";
	}
	*/
}
echo $json;
?>