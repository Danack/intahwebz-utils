<?php


namespace Intahwebz\Utils;

use Intahwebz\Utils\UnknownFileType;
use Intahwebz\Utils\UnknownMimeType;


class Utils {

	static public $knownMimeTypesForExtensions = array(
		"3gp"	=>	"video/3gpp",
		"amr"	=>	"audio/amr",
		"apk"	=>	"application/vnd.android.package-archive",
		"au"	=>	"audio/basic",
		"aif"	=>	"audio/x-aiff",
		"aifc"	=>	"audio/x-aiff",
		"aiff"	=>	"audio/x-aiff",
		"asf"	=>	"video/x-ms-asf",
		"avi"	=>	"video/x-msvideo",
		"bmp"	=>	"image/x-ms-bmp",
		"bz2"	=>	"application/x-bzip2",
		"css"	=>	"text/css",
		"csv"	=>	"text/comma-separated-values",
		"doc"	=>	"application/msword",
		"docx"	=>	"application/msword",
		"exe"	=>	"application/octet-stream",
		"flac"	=>	"audio/flac",
		"gif"	=>	"image/gif",
		"gz"	=>	"application/x-gzip",
		"gzip"	=>	"application/x-gzip",
		"html"	=>	"text/html",
		"ics"	=>	"text/calendar",
		"jpe"	=>	"image/jpeg",
		"jpeg"	=>	"image/jpeg",
		"jpg"	=>	"image/jpeg",
		"kml"	=>	"application/vnd.google-earth.kml+xml",
		"kmz"	=>	"application/vnd.google-earth.kmz",
		"mid"	=>	"audio/mid",
		"mp3"	=>	"audio/mpeg",
		"m4a"	=>	"audio/mp4",
		"mov"	=>	"video/quicktime",
		"mp3"	=>	"audio/mpeg",
		"mp4"	=>	"video/mp4",
		"mpg"	=>	"video/mpeg",
		"mpeg"	=>	"video/mpeg",
		"mpe"	=>	"video/mpeg",
		"mov"	=>	"video/quicktime",
		"ogv"	=>	"video/ogg",
		"odp"	=>	"application/vnd.oasis.opendocument.presentation",
		"ods"	=>	"application/vnd.oasis.opendocument.spreadsheet",
		"odt"	=>	"application/vnd.oasis.opendocument.text",
		"oga"	=>	"audio/ogg",
		"ogg"	=>	"audio/ogg",
		"pdf"	=>	"application/pdf",
		"php"	=>	"application/x-php",
		"png"	=>	"image/png",
		"pdf"	=>	"application/pdf",
		"pptx"	=>	"application/vnd.ms-powerpoint",
		"pps"	=>	"ppt application/vnd.ms-powerpoint",
		"rmi"	=>	"audio/mid",
		"qt"	=>	"video/quicktime",
		"smil"	=>	"application/smil",
		"snd"	=>	"audio/basic",
		"swf"	=>	"application/x-shockwave-flash",
		"sxc"	=>	"application/vnd.sun.xml.calc",
		"sxw"	=>	"application/vnd.sun.xml.writer",
		"tar"	=>	"application/x-tar",
		"text"	=>	"text/plain",
		"txt"	=>	"text/plain",
		"tif"	=>	"image/tiff",
		"tiff"	=>	"image/tiff",
		"txt"	=>	"text/plain",
		"vcf"	=>	"text/x-vcard",
		"wav"	=>	"audio/wav",
		"wbmp"	=>	"image/vnd.wap.wbmp",
		"wma"	=>	"audio/x-ms-wma",
		"wmv"	=>	"video/x-ms-wmv",
		"wsdl"	=>	"application/wsdl+xml",
		"xls"	=>	"application/vnd.ms-excel",
		"xlsx"	=>	"application/vnd.ms-excel",
		"zip"	=>	"application/x-zip-compressed"
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


	public static function getFileExtensionForMimeType($mimeType){
		$mimeType = strtolower($mimeType);
		$arrayKey = array_search ($mimeType, self::$knownMimeTypesForExtensions);

		if ($arrayKey === false) {
			throw new UnknownMimeType($mimeType, "Unknown mime type [$mimeType], cannot return extension.");
		}

		return self::$knownMimeTypesForExtensions[$mimeType];
	}

	public static function setFileType($extension, $mimeType) {
		self::$knownMimeTypesForExtensions[$extension] = $mimeType;
	}
}



?>