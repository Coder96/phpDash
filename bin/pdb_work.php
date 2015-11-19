#!/usr/bin/php 
<?PHP

error_reporting(E_ALL);

$pgmName = 'pdb_curl';
$pdbBaseDir = '/opt/phpDashButton/';
$pdbConfig = $pdbBaseDir . 'config/pdb_config.ini';

$pdbConfig = parse_ini_file($pdbConfig, true);

$iMacAddress = $argv[1];
//$ = $argv[1]


//echo("$iMacAddress\n");
	$system = $pdbConfig['MAC_' . $iMacAddress]['system']; 
	$action = $pdbConfig['MAC_' . $iMacAddress]['action'];
	if($action == 'url'){
		$url = $pdbConfig['MAC_' . $iMacAddress]['url'];
	} else {
		echo("No url found\n");
		exit();
	}
	$servicUrl = $pdbConfig['System_' . $system]['url'] . $url;
	$servicUser = $pdbConfig['System_' . $system]['user'];
	$servicPass = $pdbConfig['System_' . $system]['pass'];
	
//	var_dump($pdbConfig['System_' . $system]);
	
//	$service_url = "{$pdbConfig['UDI994i']['URL']}{$pdbConfig['10:ae:60:4f:44:83']['urlpath']}";
//	$servicUrl = "{$pdbConfig['System_UDI994i']['url']}/rest/programs/";
//	echo("$servicUser $servicPass $servicUrl\n");
	
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

	$curl = curl_init($servicUrl);
	curl_setopt($curl, CURLOPT_USERPWD, $servicUser . ":" . $servicPass);
 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	
//	curl_setopt_array($curl, $options);
	
	   
	$curl_response = curl_exec($curl);
	$status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
#	print_r("$status_code|$curl_response\n");
   
   
 
if ($curl_response === false)
{
    // throw new Exception('Curl error: ' . curl_error($crl));
    print_r('Curl error: ' . curl_error($curl));
}
   
   curl_close($curl);