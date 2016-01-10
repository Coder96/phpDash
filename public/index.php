<?PHP

$pgmName = 'index.php';

$pdbBaseDir = '/opt/phpDashButton/';
$pdbConfig = $pdbBaseDir . 'config/pdb_config.ini';

$pdbConfig = parse_ini_file($pdbConfig, true);

if(!loginGood($_POST['loginname'], $_POST['loginpass'])){
	loginForm();
	exit();
}

ToolBar();



if($_POST["toolbar"] == 'General Settings'){
	General_Settings();
} elseif($_POST["toolbar"] == 'List Systems'){
	List_Systems();
} elseif($_POST["toolbar"] == 'Add System'){
	Add_System();
} elseif($_POST["toolbar"] == 'List MACs'){
	List_MACs();
} elseif($_POST["toolbar"] == 'Add MAC'){
	Add_MAC();
} elseif($_POST["toolbar"] == 'zz'){
	
} else {
	
}

//print_r($_REQUEST);

#*************************************************************************************************************
function ToolBar(){
	echo '<form method=post >';
	echo "<input type=hidden name=loginname value='{$_POST['loginname']}' />";
	echo "<input type=hidden name=loginpass value='{$_POST['loginpass']}' />";
	echo "<input type=submit name=toolbar value='General Settings'	/>";
	echo "<input type=submit name=toolbar value='List Systems'			/>";
	echo "<input type=submit name=toolbar value='Add System'				/>";
	echo "<input type=submit name=toolbar value='List MACs'					/>";
	echo "<input type=submit name=toolbar value='Add MAC'/>";
//	echo "<input type=submit name=toolbar value=''/>";
//	echo "<input type=submit name=toolbar value=''/>";
//	echo "<input type=submit name=toolbar value=''/>";
	echo '</form>';
	}
#*************************************************************************************************************
function General_Settings(){
	
	global $pdbConfig;
	
	echo ("
<form method=post >
	<input type=hidden name=loginname value='{$_POST['loginname']}' >
	<input type=hidden name=loginpass value='{$_POST['loginpass']}' >
	<input type=hidden name=process value='UpdateGeneralSettings' >
  <table width='400' border='0'  cellpadding='5' cellspacing='1' class='Table'>
    <tr>
    	<td></td>
      <td align='left' >General Settings</td>
    </tr>
    <tr>
      <td align='right' >Username</td>
      <td><input name='loginname' type='text' class='Input' value='{$pdbConfig['General']['User']}' ></td>
    </tr>
    <tr>
      <td align='right'>Password</td>
      <td><input name='loginpass' type='text' class='Input' value='{$pdbConfig['General']['Pass']}' ></td>
    </tr>
    <tr>
      <td> </td>
      <td><input name='Submit' type='submit' value='Update' ></td>
    </tr>
  </table>
</form>");
}
#*************************************************************************************************************
function List_MACs(){
	
	listEntities('MACs');
	
}
#*************************************************************************************************************
function List_Systems(){
	
	listEntities('Systems');
	
}
#*************************************************************************************************************
function listEntities($type){
	
		global $pdbConfig;
		
		if($type == 'Systems'){
			$prefix = 'System_';
			$feild1 = 'description';
		} else {
			$prefix = 'MAC_';
			$feild1 = 'description';
		}
	
	echo("
  <table width='400' border='0'  cellpadding='5' cellspacing='1' class='Table'>
    <tr>
    	<td align='left' >$type List</td>
    </tr>
    ");	
	foreach($pdbConfig as $heading => $key){

		if(substr($heading, 0, strlen($prefix)) == $prefix){
    	echo("
    <tr>
    	<form method=post >
				<input type=hidden name=loginname value='{$_POST['loginname']}' >
				<input type=hidden name=loginpass value='{$_POST['loginpass']}' >
				<input type=hidden name=lastaction value='List' >
				<input type=hidden name=list value='$type' >
				<input type=hidden name=edit value='" . substr($heading, strlen($prefix)) . "' >
				
      	<td align='left' >" . substr($heading, strlen($prefix)) . " - {$key[$feild1]} </td>
      	
      	<td><input name='toolbar' type='submit' value='Edit' ></td>
    	</form>
	</tr>
    ");
  	}
  }
  echo("
    <tr>  
      <td></td>
    </tr>
  </table>
			");	
}
#*************************************************************************************************************
function edit($iType){
	
	echo("
  <table width='400' border='0'  cellpadding='5' cellspacing='1' class='Table'>
    <tr>
    	<td align='left' >$type List</td>
    </tr>
    ");	
	
	
	
	
	echo("
    <tr>  
      <td></td>
    </tr>
  </table>
			");
	
	
}
#*************************************************************************************************************
#*************************************************************************************************************
function loginGood($loginName, $loginPass){
	
	global $pdbConfig;
	
//	echo("$loginName, $loginPass\n");
	if( isset($loginName) || isset($loginPass) ){
		if( empty($loginName) OR empty($loginPass) ) {
//			echo ("ERROR: Please enter password!");
			return false;
		}
    
		if( $loginName == $pdbConfig['General']['user'] && $loginPass == $pdbConfig['General']['pass'] ){
			// Authentication successful - Set session
//			session_start();
//			$_SESSION['auth'] = 1;
//			setcookie("username", $_POST['loginname'], time()+(84600*30));
//			echo "Access granted!";
			return true;
		} else {
//			echo "ERROR: Incorrect username or password!";
			return false;
		}
	}
	return false;
}
#*************************************************************************************************************
function loginForm($msg=''){
	echo('
<form action="" method="post" name="Login_Form">
  <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="Table">
  ');
	if(isset($msg)){
		echo('
    <tr>
			<td colspan="2" align="center" valign="top"><?php echo $msg;?></td>
    </tr>
		');
	}
 	echo('
    <tr>
      <td></td>
      <td align="left" valign="top"><h3>Login</h3></td>
    </tr>
    <tr>
      <td align="right" valign="top">Username</td>
      <td><input name="loginname" type="text" class="Input" ></td>
    </tr>
    <tr>
      <td align="right">Password</td>
      <td><input name="loginpass" type="password" class="Input" ></td>
    </tr>
    <tr>
      <td> </td>
      <td><input name="Submit" type="submit" value="Login" ></td>
    </tr>
  </table>
</form>
');
}
#*************************************************************************************************************
function write_php_ini($array, $file)
{
    $res = array();
    foreach($array as $key => $val)
    {
        if(is_array($val))
        {
            $res[] = "[$key]";
            foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
        }
        else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
    }
    safefilerewrite($file, implode("\r\n", $res));
}
#*************************************************************************************************************
function safefilerewrite($fileName, $dataToSave)
{    if ($fp = fopen($fileName, 'w'))
    {
        $startTime = microtime(TRUE);
        do
        {            $canWrite = flock($fp, LOCK_EX);
           // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
           if(!$canWrite) usleep(round(rand(0, 100)*1000));
        } while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

        //file was locked so now we can store information
        if ($canWrite)
        {            fwrite($fp, $dataToSave);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

}