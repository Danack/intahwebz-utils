<?php

namespace Intahwebz\Utils;


trait SafeAccess {
	public function __set($name, $value) {
		throw new \Exception("Property [$name] doesn't exist for class [".get_class($this)."] so can't set it");
	}
	public function __get($name) {
		throw new \Exception("Property [$name] doesn't exist for class [".get_class($this)."] so can't get it");
	}
}


//public function __call($name, $arguments)
//{
//	// Note: value of $name is case sensitive.
//	echo "Calling object method '$name' "
//		. implode(', ', $arguments). "\n";
//}
//
///**  As of PHP 5.3.0  */
//public static function __callStatic($name, $arguments)
//{
//	// Note: value of $name is case sensitive.
//	echo "Calling static method '$name' "
//		. implode(', ', $arguments). "\n";
//}


