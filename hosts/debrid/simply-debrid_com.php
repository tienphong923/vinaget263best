<?php
$account = trim($this->get_account('simply-debrid.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("simply-debrid.com");
		if(!$cookie){
			$data = $this->curl("http://simply-debrid.com/index.php",'',"");
			$cookie = $this->GetCookies($data);
			$data = $this->curl("http://simply-debrid.com/login.php",$cookie,"");
			$cookie = $cookie.';'.$this->GetCookies($data);
			$data = $this->curl("http://simply-debrid.com/login",$cookie,'username='.urlencode($user).'&password='.urlencode($pass).'&submitButton=');
			if (!stristr($data,'document.location.href="generate"')) die('account dead !!!');
			$data = $this->curl("http://simply-debrid.com/generate",$cookie,'');
			$cookie = $cookie.';'.$this->GetCookies($data);
			$this->save_cookies("simply-debrid.com",$cookie);
		}
		$this->cookie = $cookie;
		$data = $this->curl("http://simply-debrid.com/inc/name.php?j=".base64_encode($url),$cookie,'',0);
		if (stristr($data,"free users") || stristr($data,"non PREMIUM")) {
			$this->save_cookies("simply-debrid.com","");
			die('This host is not available for free users');
		}
		elseif (stristr($data,"NOT SUPPORTED")) die('not support by simply-debrid.com');
		elseif (stristr($data,"UNDER MAINTENANCE")) die('UNDER MAINTENANCE');
		elseif(preg_match('%(http\:\/\/.*?)%U', $data, $linkpre) && stristr($data,"sd.php")){
			$link = trim($linkpre[1]);
			$size_name = Tools_get::size_name($link, $this->cookie);
			$filesize = $size_name[0];
			$filename = $size_name[1];
			break;
		}
		else {
			$cookie = "";
			$this->save_cookies("simply-debrid.com","");
		}
	}
}


/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: ..:: [H] ::.. (Sunday, October 21, 2012)
*/
?>