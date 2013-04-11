<?php

namespace Intahwebz\Utils;

trait JSONFactory{

	static function factory($data){
		$object = new static();

		foreach ($data AS $key => $value){
			if($key != 'ObjectType'){
				$object->$key = $value;
			}
		}

		return $object;
	}

	static function	fromJSON($jsonString){
		$data = json_decode($jsonString);

		if(array_key_exists('ObjectType', $data) == TRUE){
			//Could do sanity check on type here.
			unset($data['ObjectType']);
		}

		return self::factory($data);
	}

	function	toJSON(){
		$classInfo = parse_classname(get_class($this));
		$className = $classInfo['classname'];
		return json_encode_object($this, $className);
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