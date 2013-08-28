<?php



function	curlDownloadFileAndReturnHeaders($url, $fileHandle){

	$ch = curl_init();

	$headers = array();

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_NOPROGRESS, FALSE);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 1024);
	curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FILE, $fileHandle);

	curl_exec($ch);

	$responseInfo = curl_getinfo($ch);

	curl_close($ch);

	return $responseInfo;
}


