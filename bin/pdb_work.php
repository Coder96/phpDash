#!/usr/bin/php 
<?PHP

error_reporting(E_ALL);

$pgmName = 'pdb_curl';
$pdbBaseDir = '/opt/phpDashButton/';
$pdbConfig = $pdbBaseDir . 'config/pdb_config.ini';

$pdbConfig = parse_ini_file($pdbConfig, true);

$MacAddress = $argv[1]
//$ = $argv[1]



	$system = $pdbConfig['MAC_' . $MacAddress]['System']; 
	$action = $pdbConfig['MAC_' . $MacAddress]['Action'];
	if($action == 'URL'){
		$url = $pdbConfig['MAC_' . $MacAddress]['URL'];
	} else {
		echo("No url found\n");
		exit();
	}
	$servicUrl = $pdbConfig['System_' . $MacAddress]['url'] . $url;
	$servicUser = $pdbConfig['System_' . $MacAddress]['user'];
	$servicPass = $pdbConfig['System_' . $MacAddress]['pass'];
	
	
//	$service_url = "{$pdbConfig['UDI994i']['URL']}{$pdbConfig['10:ae:60:4f:44:83']['urlpath']}";
//	$service_url = "{$pdbConfig['system_UDI994i']['URL']}/rest/programs/?subfolders=true";
	echo("$service_url\n");
	$curl = curl_init($service_url);
	curl_setopt($curl, CURLOPT_USERPWD, $servicUser . ":" . $servicPass);
 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	$status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);   
	$curl_response = curl_exec($curl);
	print_r("$status_code|$curl_response\n");
   
   
 
if ($curl_response === false)
{
    // throw new Exception('Curl error: ' . curl_error($crl));
    print_r('Curl error: ' . curl_error($curl));
}
   
   curl_close($curl);