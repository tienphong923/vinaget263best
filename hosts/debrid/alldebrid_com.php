<?php
$account = trim($this->get_account('alldebrid.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("alldebrid.com");
		if(!$cookie){
			$data = $this->curl("http://www.alldebrid.com/register/?action=login&returnpage=&login_login=".urlencode($user)."&login_password=".urlencode($pass),"","login_login=".urlencode($user)."&login_password=".urlencode($pass));
			if(preg_match("%uid=(.*);%U", $data, $matches)) {
				$cookie = $matches[1];
				$this->save_cookies("alldebrid.com",$cookie);
			}
		}
		$cookie = preg_replace("/(UID=|uid=|Uid=)/","",$cookie);
		$this->cookie = "uid=".$cookie;
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $pass = $linkpass[1];
		}
		$data = $this->curl("http://www.alldebrid.com/service.php?link=".urlencode($url)."&nb=0&json=true&pw=$pass","uid=".$cookie,"");
		if (stristr($data,"disable for trial account")) $report = Tools_get::report($url,"disabletrial");
		elseif (stristr($data,"Ip not allowed")) die("<font color=red><b>Ip host have been banned by alldebird.com !</b></font>");
		if(preg_match('/"link":"(http.+)","host/i', $data, $linkpre)){
			$link = trim($linkpre[1]);
			$link = str_replace("\\", "",  $link);
			$size_name = Tools_get::size_name($link, $this->cookie);
			if($size_name[0] > 200 ){
				$filesize =  round($size_name[0]/(1024*1024),2)." MB";
				$filename = $size_name[1];
			}
			else continue;
			break;
		}
		else {
			$cookie = "";
			$this->save_cookies("alldebrid.com","");
		}
	}
}

/*
* Home page: http://vinaget.us
* Script Name: Vinaget 
* Version: gate2
* Created: ..:: [H] ::.. 
* Updated: Saturday, January 05, 2013
*/
?>