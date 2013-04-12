<?php

//TODO - either remove this or generate Javascript from this.

function rgbToHSL($hexcode){

	$redhex  = substr($hexcode,0,2);
	$greenhex = substr($hexcode,2,2);
	$bluehex = substr($hexcode,4,2);

	// $var_r, $var_g and $var_b are the three decimal fractions to be input to our RGB-to-HSL conversion routine
	$var_r = (hexdec($redhex)) / 255;
	$var_g = (hexdec($greenhex)) / 255;
	$var_b = (hexdec($bluehex)) / 255;

    // Input is $var_r, $var_g and $var_b from above
    // Output is HSL equivalent as $h, $s and $l — these are again expressed as fractions of 1, like the input values

    $var_min = min($var_r,$var_g,$var_b);
    $var_max = max($var_r,$var_g,$var_b);
    $del_max = $var_max - $var_min;

    $l = ($var_max + $var_min) / 2;
	$h = 0;
	$s = 0;

    if ($del_max == 0)
    {
            $h = 0;
            $s = 0;
    }
    else
    {
            if ($l < 0.5)
            {
                    $s = $del_max / ($var_max + $var_min);
            }
            else
            {
                    $s = $del_max / (2 - $var_max - $var_min);
            };

            $del_r = ((($var_max - $var_r) / 6) + ($del_max / 2)) / $del_max;
            $del_g = ((($var_max - $var_g) / 6) + ($del_max / 2)) / $del_max;
            $del_b = ((($var_max - $var_b) / 6) + ($del_max / 2)) / $del_max;

            if ($var_r == $var_max)
            {
                    $h = $del_b - $del_g;
            }
            else if ($var_g == $var_max)
            {
                    $h = (1 / 3) + $del_r - $del_b;
            }
            else if ($var_b == $var_max)
            {
                    $h = (2 / 3) + $del_g - $del_r;
            };

            if ($h < 0)
            {
                    $h += 1;
            };

            if ($h > 1)
            {
                    $h -= 1;
            };
    };

	$color = array();
	$color['luminance']	= $l;
	$color['hue']		= $h;
	$color['saturation'] = $s;

	return $color;
}




function compareLightness($colorA, $colorB){

	$hslA = rgbToHSL($colorA);
	$hslB = rgbToHSL($colorB);

	$lightnessA = $hslA['luminance'];
	$lightnessB = $hslB['luminance'];

	if ($lightnessA == $lightnessB) {
        return 0;
    }
	
    if($lightnessA < $lightnessB){
		return -1;
	}
	else{
		return 1;
	}
}


function compareSaturation($colorA, $colorB){

	$hslA = rgbToHSL($colorA);
	$hslB = rgbToHSL($colorB);

	$saturationA = $hslA['saturation'];
	$saturationB = $hslB['saturation'];

	if ($saturationA == $saturationB) {
        return 0;
    }

    if($saturationA < $saturationB){
		return -1;
	}
	else{
		return 1;
	}
}


function compareHue($colorA, $colorB){

	$hslA = rgbToHSL($colorA);
	$hslB = rgbToHSL($colorB);

	$hueA = $hslA['hue'];
	$hueB = $hslB['hue'];

	if ($hueA == $hueB) {
        return 0;
    }

    if($hueA < $hueB){
		return -1;
	}
	else{
		return 1;
	}
}

function html2rgb($color)
{
    // if ($color[0] == '#')
        // $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}

function rgb2html($r, $g=-1, $b=-1)
{
    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    return $color;
}


function	setSortedColours($smarty, $colorNameArray){

	$count = 0;

	foreach($colorNameArray as $colourName => $color){

		$coloursInfo = array();

		$coloursInfo['colourName'] = $colourName;
		$coloursInfo['colour'] = '#'.$color;

		$colourAsRGBArray = html2rgb($color);

		$colourAsHex = ($colourAsRGBArray[0] << 16) + ($colourAsRGBArray[1] << 16) + ($colourAsRGBArray[2]);

		$coloursInfo['colourReverse'] = '#'.dechex(0xffffff ^ $colourAsHex);

		$colorInfo = rgbToHSL($color);

		$sortMode = getVariable('sortMode', 'hueLuminance');

		switch($sortMode){

			case('saturationHue'):{
				$coloursInfo['positionX'] = intval($colorInfo['hue'] * 256 * 2);
				$coloursInfo['positionY'] = intval($colorInfo['saturation'] * 256 * 2);
				break;
			}

			case('saturationLuminance'):{
				$coloursInfo['positionX'] = intval($colorInfo['luminance'] * 256 * 2);
				$coloursInfo['positionY'] = intval($colorInfo['saturation'] * 256 * 2);
				break;
			}

			case('nosort'):{
				$xCount = ($count % 16);
				$yCount = ($count - $xCount) / 16;
				$coloursInfo['positionX'] = intval($xCount * 16 * 2);
				$coloursInfo['positionY'] = intval($yCount * 16 * 2);
				$count++;
				break;
			}

			default:
			case('hueLuminance'):{
				$coloursInfo['positionX'] = intval($colorInfo['luminance'] * 256 * 2);
				$coloursInfo['positionY'] = intval($colorInfo['hue'] * 256 * 2);
				break;
			}
		}

		$coloursInfo['luminance']	= intval($colorInfo['luminance'] * 256);
		$coloursInfo['hue'] 			= intval($colorInfo['hue'] * 256);
		$coloursInfo['saturation']	= intval($colorInfo['saturation'] * 256);

		$coloursInfoArray[] = $coloursInfo;
	}

	//htmlvar_dump($envelosColoursInfoArray);
	$smarty->assign('envelosColoursInfoArray', $coloursInfoArray);
}


?>