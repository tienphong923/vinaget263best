<?php
ob_start();
if( !ini_get('safe_mode') ){
            set_time_limit(60);
} 
error_reporting(0); 
ignore_user_abort(TRUE);
include("config.php");
date_default_timezone_set('Asia/Saigon');

if(isset($_GET['go']) && $_GET['go'] == 'logout') setcookie("secureid", "owner", time());
elseif(isset($_POST['secure'])){
	if (!in_array($_POST['secure'], $password)) 
		die("<SCRIPT language='Javascript'>alert(\"Wrong password !\");</SCRIPT><SCRIPT language='Javascript'> history.go(-1)</SCRIPT>");
	/* mod vinaleech */
	if(isset($modvnl) && $modvnl==true) {
		$data = file_get_contents("http://vinaleech.com/ipmod/ip/".$_SERVER['REMOTE_ADDR']);
		if(stristr($data,"whitelist")==false) die("<h2>Only support for mod vinaleech.com !</h2>");
	}
	/* /mod vinaleech */
	#-----------------------------------------------
	$file = "$fileinfo_dir/log.txt";	//	Rename *.txt
	$date = date('H:i:s Y-m-d');
	$entry  = sprintf("Passlogin=%s\n", $_POST["secure"]);
	$entry .= sprintf("IP: ".$_SERVER['REMOTE_ADDR']." | Date: $date\n");
	$entry .= sprintf("------------------------------------------------------------------------\n");
	$handle = fopen($file, "a+")
	or die('<CENTER><font color=red size=3>could not open file! Try to chmod the file "<B>'.$file.'</B>" to 666</font></CENTER>');
	fwrite($handle, $entry)
	or die('<CENTER><font color=red size=3>could not write file! Try to chmod the file "<B>'.$file.'</B>" to 666</font></CENTER>');
	fclose($handle);
	#-----------------------------------------------
	setcookie("secureid",md5($_POST['secure']),time()+3600*24*7);
}

header("location:./");
ob_end_flush();
?>

