<?php


namespace Intahwebz\Utils;

class Utils {

	var $knownMimeTypesForExtensions = array(
		"amr" => "audio/amr",
		"apk" => "application/vnd.android.package-archive",
		"3gp"  =>	"video/3gpp",
		"asf"  =>	"video/x-ms-asf",
		"avi"  =>	"video/x-msvideo",
		"bz2"  =>	"application/x-bzip2",
		"exe"  =>	"application/octet-stream",
		"gif"  =>	"image/gif",
		"gz"   =>	"application/x-gzip",
		"jpe"  =>	"image/jpeg",
		"jpeg" =>	"image/jpeg",
		"jpg"  =>	"image/jpeg",
		"mov"  =>	"video/quicktime",
		"mp3"  =>	"audio/mpeg",
		"mpg"  =>	"video/mpeg",
		"mpeg" =>	"video/mpeg",
		"pdf"  =>	"application/pdf",
		"png"  =>	"image/png",
		"smil" =>	"application/smil",
		"swf"  =>	"application/x-shockwave-flash",
		"tar"  =>	"application/x-tar",
		"txt"  =>	"text/plain",
		"wav"  =>	"audio/wav",
		"wma"  =>	"audio/x-ms-wma",
		"wmv"  =>	"video/x-ms-wmv",
		"zip"  =>	"application/x-zip-compressed"
	);

	public static function getMimeTypeForFileExtension($extension){
		$extension = strtolower($extension);

		if (array_key_exists($extension, self::$knownMimeTypesForExtensions) == false) {
			//TODO - why is this giving a compiler error?
			throw new UnknownFileType($extension, "Unknown file type for extension [$extension]");
		}

		$type = self::$knownMimeTypesForExtensions[$extension];

		return $type;
	}


	function getFileExtensionForMimeType($mimeType){
		$mimeType = strtolower($mimeType);
		$arrayKey = array_search ($mimeType, self::$knownMimeTypesForExtensions);

		if ($arrayKey === false) {
			throw new UnknownMimeType($mimeType, "Unknown mime type [$mimeType], cannot return extension.");
		}

		return self::$knownMimeTypesForExtensions[$mimeType];
	}
}



?>