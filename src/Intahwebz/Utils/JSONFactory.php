<?php

namespace Intahwebz\Utils;

require_once "functions.php";

trait JSONFactory {

	static function factory($data){
		$object = new static();

		foreach ($data AS $key => $value){
			//TODO - figure out what to do about defines in other files for PHP-to-javascript converter.
			//if($key != OBJECT_TYPE){
			if($key != 'x-objectType'){
				$object->$key = $value;
			}
		}

		return $object;
	}

	static function	fromJSON($jsonString){
		return json_decode_object($jsonString);
	}

	function	toJSON(){
		return json_encode_object($this);//, $className);
	}

	function	jumpToJS(){
		$jsonString = $this->toJSON();
		$output = "\n";

		//TODO - this shouldn't be hardcoded.
		$output .= "window.contentFilterData = createContentObject(json_decode('".addslashes($jsonString)."'));\n";
		return $output;
	}
}



?>