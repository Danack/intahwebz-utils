<?php

namespace Intahwebz\Utils;

//define('OBJECT_TYPE', 'x-objectType');

require_once "functions.php";

trait JSONFactory {

	static function factory($data){
		$object = new static();

		foreach ($data AS $key => $value){
			if($key != OBJECT_TYPE){
				$object->$key = $value;
			}
		}

		return $object;
	}

	static function	fromJSON($jsonString){
		return json_decode_object($jsonString);
		//return self::fromJSON($jsonData);
	}


//	static function fromJSON($jsonString){



//		$data = array();
//
//		foreach ($jsonData as $key => $value) {
//			if (is_array($value) == true) {
//				if (array_key_exists(OBJECT_TYPE, $value) == true) {
//					$className = $value[OBJECT_TYPE];
//					$value = $className::fromJSON($value);
//				}
//				else {
//					$value = self::fromJSON($value);
//				}
//			}
//
//			$data[$key] = $value;
//		}
//
//		if (array_key_exists(OBJECT_TYPE, $jsonData) == true) {
//			$objectType = $jsonData[OBJECT_TYPE];
//			$object = $objectType::factory($data);
//			return $object::factory($jsonData);
//		}
//		else {
//			return $data;
//		}
//	}

//	static function hydrate($jsonString) {
//		$data = json_decode_object($jsonString, true);
//
//		$objectType = $data[OBJECT_TYPE];
//
////		if(array_key_exists(OBJECT_TYPE, $data) == TRUE){
////			//Could do sanity check on type here.
////			unset($data[OBJECT_TYPE]);
////		}
//
//		unset($data[OBJECT_TYPE]);
//
//		$object = $objectType::factory($data);
//
//		return $object;
//	}


	function	toJSON(){
		return json_encode_object($this);//, $className);
	}

	function	jumpToJS(){
		$jsonString = $this->toJSON();
		$output = "\n";

		//TODO - this shouldn't be hardcoded.
		$output .= "window.contentFilterData = createContentObject(json_decode('".$jsonString."'));\n";
		return $output;
	}
}



?>