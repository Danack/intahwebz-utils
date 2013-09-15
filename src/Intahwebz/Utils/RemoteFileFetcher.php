<?php


namespace Intahwebz\Utils;


function	curlDownloadFileAndReturnHeaders($url, $fileHandle){

    $ch = curl_init();

    $headers = array();

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_NOPROGRESS, FALSE);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_LOW_SPEED_LIMIT, 1024);
    curl_setopt($ch, CURLOPT_LOW_SPEED_TIME, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FILE, $fileHandle);

    curl_exec($ch);

    $responseInfo = curl_getinfo($ch);

    curl_close($ch);

    return $responseInfo;
}





class RemoteFileFetcher {

    /**
     * @param $imageURL
     * @return UploadedFile
     */
    function	getImageFromLink($imageURL){
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
                $extension = Utils::getFileExtensionForMimeType($fileInfo['contentType']);
                $filename .= ".".$extension;
            }
        }

        fclose($fileHandle);

        return new UploadedFile(
            $filename,
            $tempFilename,
            filesize($tempFilename)
        );
    }
    
}

 