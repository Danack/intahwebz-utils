<?php

if(defined('NL') == FALSE){
	define('NL', "\r\n");
}


function	getContentDomain($contentID){

	$domainName = ROOT_DOMAIN;

	if(isset($_SERVER['HTTP_HOST']) == TRUE){
		$domainName = $_SERVER['HTTP_HOST'];
	}

//	if(strpos($domainName, ROOT_DOMAIN) !== FALSE){
//		//remap www.basereality.com to images.basereality.com
//		$domainName = 'content.'.ROOT_DOMAIN;
//	}

	if(CDN_ENABLED == TRUE){
		$cdnID = ($contentID % CDN_CNAMES) + 1;
		//$domainName = "cdn".$cdnID.".".ROOT_DOMAIN;
		$domainName = "cdn".$cdnID.".".$domainName;
	}

	return "http://".$domainName;
}


function	checkToRedirectToCanonicalDomain(){

	if(isset($GLOBALS['isAPIDomain']) == TRUE &&
		$GLOBALS['isAPIDomain'] == TRUE){
		return;
	}

	$domainInfo = getDomainInfo();

	if($domainInfo['shouldRedirect'] == FALSE){
		return;
	}

	$schema = 'http://';

	if(isHTTPSPage() == TRUE){
		$schema = 'https://';
	}

	$fullURL = $schema.$domainInfo['canonicalDomain'];//.'/';

	if($domainInfo['REQUEST_URI'] !== FALSE){
		$fullURL .= $domainInfo['REQUEST_URI'];
	}

	header("Location: $fullURL");
	exit(0);
}

function	isSubDomainOfCanonicalDomain($currentDomain, $canonicalDomain){

	$validPrefixes = array(
		'',
		'www.',
		'login.',
	);

	foreach($validPrefixes as $validPrefix){
		if(strcasecmp($currentDomain, $validPrefix.$canonicalDomain) === 0){
			return TRUE;
		}
	}

	return FALSE;
}

function	getDomainInfo(){

	$httpsEnabled = TRUE;

	if(isset($_SERVER['HTTP_HOST']) == TRUE){
		$serverName = $_SERVER['HTTP_HOST'];
	}
	else{
		$serverName = ROOT_DOMAIN;
	}

	$canonicalDomain = ROOT_DOMAIN; //This should look at $serverName and check whether it is one of the allowed domains
										//otherwise revert to 'ROOT_DOMAIN.

	if(isSubDomainOfCanonicalDomain($serverName, $canonicalDomain) == FALSE){ //only if we're on someone's on domain name e.g. envelos.dontexist.com
		if( defined('DEVELOPMENT_SERVER') == TRUE &&
			DEVELOPMENT_SERVER == TRUE &&
			defined('DEVELOPMENT_SERVER_DOMAIN') == TRUE){
				$canonicalDomain = DEVELOPMENT_SERVER_DOMAIN;

			if(defined('SERVER_HTTPS_ENABLED') == TRUE){
				$httpsEnabled = TRUE;
			}
		}
	}

	$currentDomainHasWWW = FALSE;

	$currentDomain = $serverName;

	if(stripos($serverName, 'www.') === 0){
		$currentDomainHasWWW = TRUE;
	}

	$domainInfo = array();
	$domainInfo['currentDomainHasWWW']	= $currentDomainHasWWW;
	$domainInfo['currentDomain'] 		= $currentDomain;
	$domainInfo['httpsEnabled'] 		= $httpsEnabled;

	$domainInfo['rootCanonicalDomain'] = $canonicalDomain;

	if($currentDomainHasWWW == TRUE){
		$domainInfo['canonicalDomain'] 		= 'www.'.$canonicalDomain;
	}
	else{
		$domainInfo['canonicalDomain'] 		= $canonicalDomain;
	}

	if(isSubDomainOfCanonicalDomain($currentDomain, $canonicalDomain) == TRUE){
		$domainInfo['shouldRedirect']	= FALSE;
	}
	else{
		$domainInfo['shouldRedirect']	= TRUE;
	}

	if(isset($_SERVER['REQUEST_URI']) == TRUE){
		$domainInfo['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
	}
	else{
		$domainInfo['REQUEST_URI'] = FALSE;
	}

	return	$domainInfo;
}

function	getURLForCurrentDomain($path, $secure = FALSE){

	$domainInfo = getDomainInfo();

	$schema = 'http://';

	if($secure == TRUE && defined ('SERVER_HTTPS_ENABLED') && SERVER_HTTPS_ENABLED){
		$schema = 'https://';
	}

	$fullURL = $schema.$domainInfo['canonicalDomain'].$path;

	return $fullURL;
}

// function	getQualifiedServerName(){

	// $domainInfo = getDomainInfo();
	// return 	$domainInfo['canonicalDomain'];
// }


//function	getAllowedSignupDomain(){
//
//	$domainInfo = getDomainInfo();
//	//htmlvar_dump($domainInfo);
//	return 	$domainInfo['canonicalDomain'];
//}

//function	contentAdded($contentID){
//	movedHeader(
//		array(
//			 'contentAdded' => true,
//			 'contentID' => $contentID,
//		)
//	);
//}

function	movedHeader($paramsAndValues){

	$urlInfo = parse_url($_SERVER['PHP_SELF']);

	$urlPath = $urlInfo['path'];

	$joinString = '?';

	foreach($paramsAndValues as $param => $value){
		//TODO URL encode params and values
		$urlPath .= $joinString.$param.'='.$value;
		$joinString = '&';
	}

	header("Location: ".$urlPath);
	exit(0);
}

?>