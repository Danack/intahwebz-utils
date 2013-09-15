<?php


namespace Intahwebz\Utils;


class UploadedFile {

    var $name;
    var $tmpName;
    var $size;

    var	$contentType;

    //TODO - where is this meant to be set.
    var $defaultContentType = null;

    function	__construct($name, $tmpName, $size){
        $this->name = $name;
        $this->tmpName = $tmpName;
        $this->size = $size;

        $this->determineContentType();
    }

    function	determineContentType(){

        $pathInfo = pathinfo($this->name);

        if($this->contentType == FALSE){
            if(array_key_exists('extension', $pathInfo) == TRUE){
                try{
                    $contentType = Utils::getMimeTypeForFileExtension(strtolower($pathInfo['extension']));
                    $this->contentType = $contentType;
                }
                catch(UnknownMimeType $umt){
                    if(isset($this->defaultContentType) == TRUE){
                        $this->contentType = $this->defaultContentType;
                    }
                    else{
                        throw $umt;//well, we're boned.
                    }
                }
            }
        }
    }

}

 