#!/usr/bin/php 
<?PHP

	error_reporting(E_ALL);

	$pgmName = 'pdb_curl';
	$pdbBaseDir = '/opt/phpDashButton/';
	$pdbConfig = $pdbBaseDir . 'config/pdb_config.ini';

	$pdbConfig = parse_ini_file($pdbConfig, true);

	$iMacAddress = $argv[1];
	if(!isset($pdbConfig['MAC_' . $iMacAddress]) AND $pdbConfig['General']['debug'] == 1){
		echo("Mac $iMacAddress not found.");
	}
	
	$system = $pdbConfig['MAC_' . $iMacAddress]['system']; 
	if(!isset($pdbConfig['MAC_' . $iMacAddress]['system']) AND $pdbConfig['MAC_' . $iMacAddress]['debug'] == 1){
		echo("system for mac $iMacAddress not found.");
	}
	
	$action = $pdbConfig['MAC_' . $iMacAddress]['action'];
	if(!isset($pdbConfig['MAC_' . $iMacAddress]['action']) AND $pdbConfig['MAC_' . $iMacAddress]['debug'] == 1){
		echo("action for mac $iMacAddress not found.");
	}
	
	if($action == 'url'){
		$url = $pdbConfig['MAC_' . $iMacAddress]['url'];
	} else {
		echo("No url found\n");
		exit();
	}
	$servicUrl = $pdbConfig['System_' . $system]['url'] . $url;
	$servicUser = $pdbConfig['System_' . $system]['user'];
	$servicPass = $pdbConfig['System_' . $system]['pass'];
/*
	$options = array(
		CURLOPT_RETURNTRANSFER => true,   // return web page
		CURLOPT_HEADER         => false,  // don't return headers
		CURLOPT_FOLLOWLOCATION => true,   // follow redirects
		CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
		CURLOPT_ENCODING       => "",     // handle compressed
		CURLOPT_USERAGENT      => "test", // name of client
		CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
		CURLOPT_TIMEOUT        => 120,    // time-out on response
	);
*/
	$curl = curl_init($servicUrl);
	curl_setopt($curl, CURLOPT_USERPWD, $servicUser . ":" . $servicPass);
 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	$curl_response = curl_exec($curl);
	$status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
#	print_r("$status_code|$curl_response\n");
   
   
	if ($curl_response === false){
    print_r('Curl error: ' . curl_error($curl));
	}
   
  curl_close($curl);