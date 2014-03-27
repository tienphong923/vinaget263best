<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?up\.4share\.vn/#', $url)){
	$account = trim($this->get_account('4share.vn'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("4share.vn");
			if(!$cookie){
				$page = $this->curl("http://up.4share.vn/?control=login","","inputUserName=$user&inputPassword=$pass");
				$cookie = $this->GetCookies($page);
				$this->save_cookies("4share.vn",$cookie);
			}
			$this->cookie = $cookie;
			$page = $this->curl($url, $cookie, ""); 
			if(preg_match("%<a href='(http://sv.*?)'><img src='/images/download.button.png'/>%U", $page, $redir2)) $link = trim($redir2[1]);
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = ""; 
				$this->save_cookies("4share.vn","");
			}
		}
	}
}

/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: France
*/
?>