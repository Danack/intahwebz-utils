<?php


define('OBJECT_TYPE', 'x-objectType');


function parse_classname($name){
	return array(
		'namespace' => array_slice(explode('\\', $name), 0, -1),
		'classname' => join('', array_slice(explode('\\', $name), -1)),
	);
}


/**
 * Return a JSON string for all of the public variables of an object.
 *
 * This isn't a
 *
 * @param $object
 * @return string
 */
function json_encode_object_internal($object){

	$params = array();

	//$type = false;

	if (is_object($object) == true) {
		$traits = class_uses($object);
		if (in_array('Intahwebz\\Utils\\JSONFactory', $traits)) {
			$type = get_class($object);
			$params[OBJECT_TYPE] = $type;
		}
	}

	foreach ($object as $key => $value) {
		if (is_object($value) == true) {
			$traits = class_uses($value);
			if (in_array('Intahwebz\\Utils\\JSONFactory', $traits)) {
				//$className = get_class($value);
				$value = json_encode_object_internal($value);
			}
		}
		else if (is_array($value) == true) {
			$arrayValues = array();
			foreach ($value as $arrayKey => $arrayValue) {
				$arrayValues[$arrayKey] = $arrayValue;
			}
			$value = $arrayValues;
		}

		$params[$key] = $value;
	}

	return $params;
}

function json_encode_object($object){
	$params = json_encode_object_internal($object);
	return json_encode($params, JSON_HEX_APOS|JSON_PRETTY_PRINT);
}


function json_decode_object($jsonString){
	$jsonData = json_decode($jsonString, true);
	return json_decode_object_internal($jsonData);
}


function json_decode_object_internal($jsonData){
	$data = array();

	foreach ($jsonData as $key => $value) {
		if (is_array($value) == true) {
//			if (array_key_exists(OBJECT_TYPE, $value) == true) {
//				$className = $value[OBJECT_TYPE];
//				$value = $className::factory($value);
//			}
//			else {
				$value = json_decode_object_internal($value);
//			}
		}

		$data[$key] = $value;
	}

	if (array_key_exists(OBJECT_TYPE, $jsonData) == true) {
		$objectType = $jsonData[OBJECT_TYPE];
		$object = $objectType::factory($data);
		return $object;//$object::factory($jsonData);
	}
	else {
		return $data;
	}
}

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



?>