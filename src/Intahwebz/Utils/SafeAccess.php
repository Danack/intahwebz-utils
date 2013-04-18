<?php

namespace Intahwebz\Utils;


trait SafeAccess {
	public function __set($name, $value) {
		throw new Exception("Property [$name] doesn't exist for class [".__CLASS__."] so can't set it");
	}
	public function __get($name) {
		throw new Exception("Property [$name] doesn't exist for class [".__CLASS__."] so can't get it");
	}
}




?>