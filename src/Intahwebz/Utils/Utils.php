<?php


namespace Intahwebz\Utils;


class Utils {

	public static function getMimeType($extension){

		$extension = strtolower($extension);

		$type = "text/plain";

		switch($extension){

			case "amr": 	$type = "audio/amr";    				break;
			case "apk": 	$type = "application/vnd.android.package-archive";				break;

			case "3gp":     $type = "video/3gpp";               	break;
			case "asf":     $type = "video/x-ms-asf";                break;
			case "avi":     $type = "video/x-msvideo";               break;
			case "bz2":     $type = "application/x-bzip2";               break;
			//case "exe":     $type = "application/octet-stream";      break;
			case "gif":		$type = "image/gif";					 break;
			case "gz":		$type = "application/x-gzip";					 break;

			case "jpe":		$type = "image/jpeg";					 break;
			case "jpeg":	$type = "image/jpeg";					 break;
			case "jpg":		$type = "image/jpeg";					 break;
			case "mov":     $type = "video/quicktime";               break;
			case "mp3":     $type = "audio/mpeg";                    break;
			case "mpg":     $type = "video/mpeg";                    break;
			case "mpeg":    $type = "video/mpeg";                    break;
			case "pdf":		$type = "application/pdf";				 break;
			case "png":		$type = "image/png";					 break;
			case "smil":	$type = "application/smil";				 break;
			case "swf":		$type = "application/x-shockwave-flash";	break;

			case "tar":		$type = "application/x-tar";	break;


			//case "rar":     $type = "encoding/x-compress";           break;
			case "txt":     $type = "text/plain";                    break;
			case "wav":     $type = "audio/wav";                     break;
			case "wma":     $type = "audio/x-ms-wma";                break;
			case "wmv":     $type = "video/x-ms-wmv";                break;

			case "zip":     $type = "application/x-zip-compressed";  break;
			//default:        $type = "application/force-download";    break;
			//default: echo "Unknown file type for extension [$extension]\r\n"; exit(0);break;
			default:{
				throw new  UnknownMimeType($extension, "Unknown file type for extension [$extension]");
			}
		}

		return $type;
	}



	function getFileExtension($mimeType){

		$mimeType = strtolower($mimeType);

		switch($mimeType){

			case "audio/amr": 			$type = 	"amr";		 break;
			case "video/3gpp":     		$type =     "3gp";   	 break;
			case "video/x-ms-asf":     	$type =     "asf";        break;
			case "video/x-msvideo":     $type =     "avi";        break;
			case "image/gif":			$type = 	"gif";		 break;
			//case "image/jpeg":			$type = 	"jpe";		 break;
			//case "image/jpeg":			$type = 	"jpeg";		 break;
			case "image/jpeg":			$type = 	"jpg";		 break;
			case "video/quicktime":     $type =     "mov";        break;
			case "audio/mpeg":     		$type =     "mp3";        break;
			case "video/mpeg":     		$type =     "mpg";        break;
			//case "video/mpeg":    		$type =     "mpeg";       break;
			case "image/png":			$type = 	"png";		 break;
			case "application/smil":	$type = 	"smil";		 break;
			case "text/plain":     		$type =     "txt";        break;
			case "audio/wav":     		$type =     "wav";        break;
			case "audio/x-ms-wma":     	$type =     "wma";        break;
			case "video/x-ms-wmv":     	$type =     "wmv";        break;

			//case "zip":     $type = "application/x-zip-compressed";  break;

			//default:        $type = "application/force-download";    break;

			default: echo "Unknown mime type [$mimeType]\r\n"; exit(0);				 break;
		}

		return $type;
	}

}



?>