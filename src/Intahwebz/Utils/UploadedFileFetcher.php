<?php


namespace Intahwebz\Utils;



function getNormalizedFILES(){
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

function getFileUploadErrorMeaning($errorCode){

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
        return	"There was a problem with your upload, error code is ".$errorCode;
        }
    }
}


class UploadedFileFetcher {

    /**
     * @param $formFileName
     * @return UploadedFile
     * @throws FileUploadException
     * @throws \Exception
     */
    function getUploadedFile($formFileName){
        //logToFileDebug("getUploadedFileInfo");

        $files = getNormalizedFILES();

        if(isset($files[$formFileName]) == FALSE){
            //logToFileDebug_var($files);
            //throw new Exception("File not uploaded. \$files[".$formFileName."] is not set.");
            return false;
        }
        else{
            if($files[$formFileName]['error'] == UPLOAD_ERR_OK) {
                if(is_uploaded_file($files[$formFileName]['tmp_name']) ){
                    //logToFileDebug("File [$formFileName] looks valid details are ".getVar_DumpOutput($files[$formFileName]));

                    return new UploadedFile(
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
                throw new FileUploadException("Error detected in upload: ".getFileUploadErrorMeaning($files[$formFileName]['error']));
            }
        }
    }  
}

 