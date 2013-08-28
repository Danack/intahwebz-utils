<?php


namespace Intahwebz\Utils;


class UnknownMimeType extends \Exception {

	var  $fileType = null;

	function __construct($fileType, $message = "", $code = 0, $previous = NULL ){
		$this->fileType = $fileType;
		parent::__construct($message, $code, $previous);
	}

	function getFileType(){
		return $this->fileType;
	}
}

