<?php


namespace Intahwebz\Tests\Utils;

use Intahwebz\Utils\JSONFactory;

class JSONFactoryImplementation {

	use JSONFactory;

	public $publicVar;
	private $privateVar;

	public $arrayVars = array();

	public function __construct(){
	}

	public function init($public, $private){
		$this->publicVar = $public;
		$this->privateVar = $private;
	}

	public function setVar($name, $value) {
		$this->arrayVars[$name] = $value;
	}

	public function getPublic(){
		return $this->publicVar;
	}

	public function getPrivate(){
		return $this->privateVar;
	}
}



?>