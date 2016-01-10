#!/usr/bin/php 
<?PHP

$pgmName = 'pdb_monitorsyslog';
$pdbBaseDir = '/opt/phpDashButton/';
$pdbConfigFile = $pdbBaseDir . 'config/pdb_config.ini';

$pdbConfig = parse_ini_file($pdbConfigFile, true);

$pdbSyslog = '/var/log/syslog';
$pdbLockFileName = $pdbBaseDir . "config/$pgmName.lock";
$pdbPidFilename = $pdbBaseDir . "config/$pgmName.pid";

$fpLock = fopen("$pdbLockFileName", 'w+');
/* Activate the LOCK_NB option on an LOCK_EX operation */
if(!flock($fpLock, LOCK_EX | LOCK_NB)) {
//    echo 'Unable to obtain lock';
    exit();
}
//$fhEvent = fopen("$homeDir/$EventFileName", 'w');
//fclose($fhEvent);

writePidInfo("$pdbPidFilename");


//$fp = popen("tail -f {$syslog} -n1 2>&1", 'r');


for(;;){
	if(!is_readable($pdbSyslog)){
		echo("Cannot read $pdbSyslog\n");
		fclose($fpLock); 
		exit();
	}
	$fp = popen("tail -F $pdbSyslog -n0 2>&1", 'r');
	while(is_resource($fp)){
		$line = fgets($fp);
		$pdbConfig = parse_ini_file($pdbConfigFile, true); // reread config file.
		
//	Nov 10 07:43:46 raspberrypi hostapd: wlan0: STA 10:ae:60:4f:44:83 IEEE 802.11: authenticated
		$Interface = "/hostapd: wlan0:/";
		$authenticated = "/IEEE 802.11: authenticated/";
		if(preg_match($Interface, $line) AND preg_match($authenticated, $line)){
			$items = explode(' ', $line);
			if(!isValidMac($items[7])){
				$validMac = false;
				if(!isValidMac($items[6])){
					$validMac = false;
					if(!isValidMac($items[8])){
						$validMac = false;
					} else {
						$validMac = 8;
					}
				} else {
					$validMac = 6;
				}
			} else {
				$validMac = 7;
			}
			if($validMac !== false){
				$macAddress = strtoupper($items[$validMac]);
				if($pdbConfig['General']['debug'] == 1){
					echo("MAC:$macAddress\n");
				}
				if(isset($pdbConfig['MAC_' . $macAddress])){
					if($pdbConfig['MAC_' . $macAddress]['debug'] == 1){
						echo( "MAC:        $macAddress\n");
						echo( 'active:     ' . $pdbConfig['MAC_' . $macAddress]['active']      . "\n");
						echo( 'debug:      ' . $pdbConfig['MAC_' . $macAddress]['debug']       . "\n");
						echo( 'buttonname: ' . $pdbConfig['MAC_' . $macAddress]['buttonname']  . "\n");
						echo( 'description:' . $pdbConfig['MAC_' . $macAddress]['description'] . "\n");
						echo( 'system:     ' . $pdbConfig['MAC_' . $macAddress]['system']      . "\n");
						echo( 'action:     ' . $pdbConfig['MAC_' . $macAddress]['action']      . "\n");
						echo( 'url:        ' . $pdbConfig['MAC_' . $macAddress]['url']         . "\n");
					}
					if(isset($pdbConfig['MAC_' . $macAddress])){
						if($pdbConfig['MAC_' . $macAddress]['active'] == 1){
							system("$pdbBaseDir/bin/pdb_work.php '$macAddress' 2>&1 >/dev/null &");
						}
					}
				} else {
					if($pdbConfig['General']['debug'] == 1){
						echo("MAC not in ini:$macAddress \n");
					}
				}
			} else {
				if($pdbConfig['General']['debug'] == 1){
					echo("No MAC found: $line \n");
				}
			}
		}
	}
}
fclose($fpLock);

//********************************************************************************
function writePidInfo($fileName){
	
	$myPid = getmypid();
	$fpPid = popen("cat /proc/{$myPid}/cmdline", 'r');
	$pidName = fgets($fpPid);
	pclose($fpPid);
	
	$fpPid = fopen("$fileName", 'w');
	fputs($fpPid, $myPid .'|'. $pidName);
	fclose($fpPid);
}
//********************************************************************************
function isValidMac($mac)
{
  if(preg_match('/([a-fA-F0-9]{2}[:|\-]?){6}/', $mac) == 1){
  	return true;
  } else {
  	return false;
  }
}