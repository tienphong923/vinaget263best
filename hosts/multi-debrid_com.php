<?php
$account = trim($this->get_account('multi-debrid.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("multi-debrid.com");
		if(!$cookie){
			$data = $this->curl("http://multi-debrid.com","","");	
			$cookie = $this->GetCookies($data);
			$data = $this->curl("http://multi-debrid.com/login",$cookie,'user%5Bidentity%5D='.$user.'&user%5Bpass%5D='.$pass.'&action=login');	
			$cookie = $cookie.';'.$this->GetCookies($data);
			$this->save_cookies("multi-debrid.com",$cookie);
		}
		$this->cookie = $cookie;
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $pass = $linkpass[1];
		}
		$data = $this->curl('http://multi-debrid.com/ajaxdownloader?link='.urlencode($url),$cookie,"",0);
		$page = json_decode($data, true);
		if ($page['status'] == 200) {
			$link = trim($page['link']);
			$size_name = Tools_get::size_name($link, $this->cookie);
			if($size_name[0] > 300 ){
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else $link='';
		}
		else {
			$cookie = ""; 
			$this->save_cookies("multi-debrid.com","");
			die($page['statusmessage']);
		}
	}
}


/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: ..:: [H] ::.. 
* Updated:	Sunday, February 24, 2013
*/
?>