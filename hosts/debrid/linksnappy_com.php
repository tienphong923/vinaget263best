<?php
$account = trim($this->get_account('linksnappy.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("linksnappy.com");
		if(!$cookie){
			$data = $this->curl("http://linksnappy.com/members/index.php?act=login","","username=".urlencode($user)."&password=".urlencode($pass)."&submit=Login");
			$cookie = $this->GetCookies($data);
			$this->save_cookies("linksnappy.com",$cookie);
		}
		$this->cookie = $cookie;
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $pass = $linkpass[1];
		}
		$time = time();
		$data = $this->curl('http://gen.linksnappy.com/genAPI.php?callback=jQuery152034288353948601724_'.$time.'&genLinks={"link"+:+"'.$url.'",+"linkpass"+:+"'.$pass.'"}',$cookie,'');

		if(preg_match('%generated":"(.*)"%U', $data, $linkpre)){
			$link=str_replace("\\","",trim($linkpre[1]));
			$size_name = Tools_get::size_name($link, $this->cookie);
			if($size_name[0] > 200 ){
				$filesize =  $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else $link='';
		}
		else {
			$cookie = "";
			$this->save_cookies("linksnappy.com","");
		}
	}

}


/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: ..:: [H] ::..
*/
?>