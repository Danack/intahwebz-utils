<?php

namespace Intahwebz\Utils;

//define('STORAGE_PATH_IMAGE', 'images');
//define('STORAGE_PATH_BINARY', "files");


require_once "functions.php";


class UserUploadedFile {

	var $name;
	var $tmpName;
	var $size;

	var	$contentType;

	function	__construct($name, $tmpName, $size){
		$this->name = $name;
		$this->tmpName = $tmpName;
		$this->size = $size;

		$this->determineContentType();
		//$this->determineFolder();
	}

	static function getNormalizedFILES(){
		$newFiles = array();
		foreach($_FILES as $fieldName => $fieldValue){
			foreach($fieldValue as $paramName => $paramValue){
				foreach((array)$paramValue as $index => $value){
					$newFiles[$fieldName][$paramName] = $value;
				}
			}
		}
		return $newFiles;
	}

	static function getFileUploadErrorMeaning($errorCode){

		switch($errorCode){
			case (UPLOAD_ERR_OK):{ //no error; possible file attack!
				return "There was a problem with your upload.";
			}
			case (UPLOAD_ERR_INI_SIZE): {//uploaded file exceeds the upload_max_filesize directive in php.ini
				return "The file you are trying to upload is too big.";
			}
			case (UPLOAD_ERR_FORM_SIZE):{ //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
				return "The file you are trying to upload is too big.";
			}
			case UPLOAD_ERR_PARTIAL: {//uploaded file was only partially uploaded
				//Todo - allow partial uploads
				return 	"The file you are trying upload was only partially uploaded.";
			}
			case (UPLOAD_ERR_NO_FILE): {//no file was uploaded
				return 	"You must select a file for upload.";
			}

			//TODO - handle these
//			UPLOAD_ERR_NO_TMP_DIR
//			UPLOAD_ERR_CANT_WRITE
//			UPLOAD_ERR_EXTENSION

			default: {	//a default error, just in case!  :)
				return	"There was a problem with your upload.";
			}
		}
	}

//TODO - Remove project specific code
//	/**
//	 * @return string Is used in Javascript (somewhere?)
//	 */
//	function    determineFolder() {
//		switch ($this->contentType) {
//			case "image/jpeg":
//			case "image/gif":
//			case "image/png":{
//				$this->folder = STORAGE_PATH_IMAGE;
//				return $this->folder;
//			}
//
//			default:{
//				$this->folder = STORAGE_PATH_BINARY;
//				return $this->folder;
//			}
//		}
//	}

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

	/**
	 * @param $formFileName
	 * @return UserUploadedFile
	 * @throws FileUploadException
	 * @throws Exception
	 */
	static function getUserUploadedFile($formFileName){
		//logToFileDebug("getUploadedFileInfo");

		$files = self::getNormalizedFILES();

		if(isset($files[$formFileName]) == FALSE){
			//logToFileDebug_var($files);
			//throw new Exception("File not uploaded. \$files[".$formFileName."] is not set.");
			return false;
		}
		else{
			if($files[$formFileName]['error'] == UPLOAD_ERR_OK) {
				if(is_uploaded_file($files[$formFileName]['tmp_name']) ){
					//logToFileDebug("File [$formFileName] looks valid details are ".getVar_DumpOutput($files[$formFileName]));

					return new UserUploadedFile(
						$files[$formFileName]['name'],
						$files[$formFileName]['tmp_name'],
						$files[$formFileName]['size']
					);
				}
				else{
					throw new FileUploadException("File not uploaded. Status [".$files[$formFileName]['error']."] indicated error.");
				}
			}
			else{
				//var_dump($files);
				throw new FileUploadException("Error detected in upload: ".self::getFileUploadErrorMeaning($files[$formFileName]['error']));
			}
		}
	}



	/**
	 * @param $imageURL
	 * @return UserUploadedFile
	 */
	static function	getImageFromLink($imageURL){

		/*$fileTest = tempnam(sys_get_temp_dir(), "imageLink");
		//$fileTest = sys_get_temp_dir()."/fark.txt";

		$filehandle = fopen($fileTest, "w");

		if($filehandle == false){
			echo "Failed to open ".$fileTest."\n".getcwd();
			exit(0);
		}
		fwrite($filehandle, "Hello");
		fclose($filehandle); */

		$tempFilename = tempnam(sys_get_temp_dir(), 'Tux');

		$fileHandle = fopen($tempFilename, 'w+');

		$headerArray = curlDownloadFileAndReturnHeaders($imageURL, $fileHandle);

		$urlInfo = parse_url($imageURL);

		$fileInfo = array();

		foreach($headerArray as $headerKey => $headerValue){
			if(strcasecmp('Content-type', $headerKey) == 0 ||
				strcasecmp('content_type', $headerKey) == 0){
				$fileInfo['contentType'] = $headerValue;
			}
		}

		$lastSlashPosition = strrpos($urlInfo['path'], '/');

		if($lastSlashPosition === FALSE){
			$filename = $urlInfo['path'];
		}
		else{
			$filename = substr($urlInfo['path'], $lastSlashPosition + 1);//+1 to exclude the slash
		}

		if(strlen($filename) == 0){
			$filename = date("Y_m_d_H_i_s");
			//Cannot guess image type from made up file name.

			if(array_key_exists('contentType', $fileInfo) == TRUE){
				$extension = getFileExtension($fileInfo['contentType']);
				$filename .= ".".$extension;
			}
		}

		fclose($fileHandle);

		return new UserUploadedFile(
			$filename,
			$tempFilename,
			filesize($tempFilename)
		);
	}
}


?>