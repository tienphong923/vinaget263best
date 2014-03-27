<?php
$account = trim($this->get_account('real-debrid.com'));
if (stristr($account,':')) list($user, $pass) = explode(':',$account);
else $cookie = $account;
if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) $cookie = $this->get_cookie("real-debrid.com");
		if(!$cookie){
			$data = $this->curl("http://real-debrid.com/ajax/login.php?user=".urlencode($user)."&pass=".urlencode(md5($pass))."&captcha_challenge=&captcha_answer=&time=".time(),"","");
			if(preg_match('%(auth=.+);%U', $data, $cook)){
				$cookie = $cook[1];
				$this->save_cookies("real-debrid.com",$cookie);
			}
		}
		$this->cookie = $cookie;
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $pass = $linkpass[1];
		}
		$data = $this->curl("http://real-debrid.com/ajax/unrestrict.php?link=".urlencode($url)."&password=$pass&remote=0&time=".time(),$this->cookie,'',0);

		$page = json_decode($data, true);
		if (stristr($data,'Dedicated server detected'))  {
			die("<font color=red><b>Dedicated server detected, you are not allowed to generate a link !</b></font>");
		}
		elseif(isset($page['error']) && $page['error'] != '0')  die('<font color=red>'.$page['message'].'</font>');
		elseif(isset($page['error']) && $page['error']== '0') {
			$filename = $page['file_name'];
			$link = $page['generated_links'][0][2];
			$size_name = Tools_get::size_name($link, $this->cookie);
			if($size_name[0] > 200 ) $filesize = $size_name[0];
			else $link='';
			break;
		}
		else {
			$cookie = "";
			$this->save_cookies("real-debrid.com","");
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