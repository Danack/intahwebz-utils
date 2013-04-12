<?php

namespace Intahwebz\Utils;

//use Intahwebz\Utils\Utils;


function setWidthHeight($srcWidth, $srcHeight, $maxWidth, $maxHeight){

	$ret = array($srcWidth, $srcHeight);

	$ratio = $srcWidth / $srcHeight;

	if($srcWidth > $maxWidth || $srcHeight > $maxHeight){

		$ret[0] = $maxWidth;
		$ret[1] = $ret[0] / $ratio;

		if($ret[1] > $maxHeight){
			$ret[1]  = $maxHeight;
			$ret[0] = $maxHeight * $ratio;
		}
	}

	$ret[0] = intval(ceil($ret[0]));
	$ret[1] = intval(ceil($ret[1]));

	return $ret;
}

/**
 * @param $srcFileName String
 * @return mixed
 */
function createImageFromFile($srcFileName){

	$types = array('jpg' => array('imagecreatefromjpeg', 'imagejpeg'),
					'jpeg' => array('imagecreatefromjpeg', 'imagejpeg'),
					'gif' => array('imagecreatefromgif', 'imagegif'),
					'png' => array('imagecreatefrompng', 'imagepng'));

	$thepath = pathinfo($srcFileName);

	$fileExtension = strtolower($thepath['extension']);

	if(array_key_exists($fileExtension, $types) == FALSE){
		$imageInfo = array();
		$imageSize = getimagesize($srcFileName, $imageInfo);

		var_dump($imageInfo);
		exit(0);
	}

	$func = $types[$fileExtension][0];
	$src = $func($srcFileName);

	return $src;
}

function saveImageToFile($dst, $destFileName){

	$types = array(
		'jpg' => array('imagecreatefromjpeg', 'imagejpeg', 80),
		'jpeg' => array('imagecreatefromjpeg', 'imagejpeg', 80),
		'gif' => array('imagecreatefromgif', 'imagegif'),
		'png' => array('imagecreatefrompng', 'imagepng')
	);

	ensureDirectoryExists($destFileName);

	$thepath = pathinfo($destFileName);
	$fileExtension = strtolower($thepath['extension']);

	$func = $types[$fileExtension][1];

	if(isset($types[$fileExtension][2]) === TRUE){
		//echo "Using Quality ".$types[$fileExtension][2]."\r\n";
		$func($dst, $destFileName, $types[$fileExtension][2]);
	}
	else{
		$func($dst, $destFileName);
	}

	if(file_exists($destFileName) == FALSE){
		throw new Exception("Failed to save image destFileName ".$destFileName." func ".$func);
	}
}

function createthumb($srcFileName, $destFileName, $maxWidth, $maxHeight) {


	ensureDirectoryExists($destFileName);

	if (is_file($srcFileName)) {

		if(filesize($srcFileName) == 0){
			throw new InternalAPIFailedException("Trying to resize file but it's of size 0.");
		}

		$cursize = getimagesize($srcFileName);

		if ($cursize === FALSE) {
			throw new InternalAPIFailedException("Failed to read image size getimagesize(srcFileName $srcFileName)");
		}

		//TODO - change when I get a > 16MB camera
		if ($cursize[0] * $cursize[1] > 2* (4096 * 4096)) {
			$errorString = "Image size is too big > 4096 x 4096 cursize is:";
			$errorString .= getVar_DumpOutput($cursize);
			logToFileDebug($errorString);
			throw new Exception($errorString);
		}

		$newsize = setWidthHeight(
			$cursize[0],
			$cursize[1],
			$maxWidth,
			$maxHeight
		);

		$dst = imagecreatetruecolor($newsize[0], $newsize[1]);

		$src = createImageFromFile($srcFileName);

		imagealphablending( $dst, false);
		imagesavealpha($dst, true);

		imagecopyresampled($dst, $src, 0, 0, 0, 0,
			$newsize[0], $newsize[1],
			$cursize[0], $cursize[1]
		);

		saveImageToFile($dst, $destFileName);

		logToFileDebug("Image resizing complete.");
	}
	else {
		throw new Exception("Source image [$srcFileName] does not exist");
	}
}


function createThumbnailFromFileDB($imageID, $destFileName, $maxWidth, $maxHeight){

	$fileInfo = getDBFileInfoAndContents($imageID);
	
	if($fileInfo == FALSE){
		throw new Exception("Failed to retrieve image $imageID from database.");	
	}
	
	if(function_exists('imagecreatefromstring') !== TRUE){
		logToFileFatal("Error, function imagecreatefromstring doesn't exist - presumably the GD libraries are not installed.");
		exit(0);	
	}

	$image = FALSE;

	if(strcmp($fileInfo['contentType'], "image/gif") === 0){

	/*
		$dummy_file = "dummy.gif"; 

		# write the contents to a dummy file
		$output = fopen("$dummy_file", "wb");
		fwrite($output, $fileInfo['contents']);
		fclose($output);

		# create the gif from the dummy file
		$image = ImageCreateFromGif($dummy_file);

		# get rid of the dummy file
		//unlink($dummy_file);
		
		$im = new Imagick($image); */
		$im = new Imagick();

		$im->readimageblob($fileInfo['contents']);

		//$im->coalesceImages();

		$width = $im->getImageWidth();
		$height = $im->getImageHeight();

		//echo "width $width <br/>";
		//echo "height $height <br/>";

		
		$newsize = setWidthHeight( $width,
									$height,
									$maxWidth,
									$maxHeight);

		$count = $im->getNumberImages();


		for ($x = 1; $x<=$im->getNumberImages(); $x++) {
			$im->previousImage();
			$im->thumbnailImage($newsize[0], $newsize[1]);
			$im->writeImage('img'.$x.'.png');
		}

		$coalesced = $im->coalesceImages();


		$type = $im->getFormat();
		header("Content-type: $type");


		if ($coalesced->getNumberImages() > 1){
			echo $coalesced->getImagesBlob();
		}
		else{
			echo $coalesced->getImageBlob();
		}

		exit(0);
	}
	else{
		$image = @imagecreatefromstring($fileInfo['contents']);

		if ($image === FALSE){
			//logToFileError('Error loading image $imageID from database.');
			//return FALSE;
			throw new Exception("Failed to create image from data retreived from database, presumably file is corrupt.");
		}
	}



	$cursize = array();

	$cursize['0'] = imagesx($image);
	$cursize['1'] = imagesy($image); 

	if($cursize[0] * $cursize[0] > (4096 * 4096)){
		return FALSE;
	}

	$newsize = setWidthHeight( $cursize[0],
							   $cursize[1],
								$maxWidth,
								$maxHeight);

	$dst = imagecreatetruecolor($newsize[0], $newsize[1]);
	$src = $image;

	//$white = imagecolorallocate($dst, 255, 255, 255);

	//imagefilledrectangle($dst, 0, 0, $newsize[0], $newsize[1], $white);

	imagealphablending( $dst, false);
	imagesavealpha($dst, true);

	imagecopyresampled( $dst, $src, 0, 0, 0, 0,
						$newsize[0], $newsize[1],
						$cursize[0], $cursize[1]);

	//ImageTrueColorToPalette2( $dst, 10, 255);

	saveImageToFile($dst, $destFileName);
	return;
}

/* For the love of God.
 * 
 * Debian doesn't include the function "ImageColorMatch" as it has been included by the PHP developers and
 * and so is considered a branch of GD library - and so is a security risk. Because the PHP developers wrote it.
 */

function    ImageTrueColorToPalette2( &$image, $dither, $ncolors ){
    $width = imagesx( $image );
    $height = imagesy( $image );
    $colors_handle = ImageCreateTrueColor( $width, $height );
    ImageCopyMerge( $colors_handle, $image, 0, 0, 0, 0, $width, $height, 100 );
    ImageTrueColorToPalette( $image, $dither, $ncolors );
    ImageColorMatch( $colors_handle, $image );
    ImageDestroy( $colors_handle );
}






?>