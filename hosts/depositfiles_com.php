<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?depositfiles\.com/#', $url)){
	$maxacc = count($this->acc['depositfiles.com']['accounts']);
	if($maxacc > 0){
		if (isset($_POST['captcha']) && $_POST['captcha'] == 'reload') {
			$page = $this->curl("http://api.recaptcha.net/challenge?k=6LdRTL8SAAAAAE9UOdWZ4d0Ky-aeA7XfSqyWDM2m","","");
			if(preg_match("%challenge : '(.*)'%U", $page, $matches)) 
				echo 'captcha code \''.trim($matches[1]).'\' rand \''.$rand.'\'';
			else 
				echo '<font color=blue>'.$url.'</font> <font color=red>==&#9658; Authentication Required, pls contact admin@vinaget.us</font>';
			exit;
		}

		$password = "";
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $password = $linkpass[1];
		}
		if (isset($_POST['password'])) $password = $_POST['password'];
		if($password) $post = "file_password=".$password;
		else $post = "";
		$data = $this->curl($url,"lang_current=en","");
		if (preg_match('/ocation\: (http\:\/\/dfiles\.eu.*)/i', $data, $redir)) {
			$url = trim($redir[1]);
		}
		for ($k=0; $k < $maxacc; $k++){
			$account = $this->acc['depositfiles.com']['accounts'][$k];
			if (stristr($account,':')) list($user, $pass) = explode(':',$account);
			else $cookie = $account;
			if(empty($cookie)==false || ($user && $pass)){
				$url=str_replace("depositfiles.com/files","depositfiles.com/en/files",$url);
				for ($j=0; $j < 1; $j++){
					if(!$cookie) $cookie = $this->get_cookie("depositfiles.com");
					if(!$cookie){
						if(empty($_POST['recaptcha_challenge_field'])==FALSE && empty($_POST['recaptcha_response_field'])==FALSE){
							$key = $_POST['recaptcha_challenge_field'];
							$value = $_POST['recaptcha_response_field'];
							$page = $this->curl("http://depositfiles.com/api/user/login","lang_current=en","login=$user&password=$pass&recaptcha_challenge_field=$key&&recaptcha_response_field=$value");
						}
						else $page=$this->curl("http://depositfiles.com/api/user/login","lang_current=en","login=$user&password=$pass&recaptcha_challenge_field=&recaptcha_response_field=");
						if(stristr($page,'"error":"CaptchaRequired"')) {
							$page = $this->curl("http://api.recaptcha.net/challenge?k=6LdRTL8SAAAAAE9UOdWZ4d0Ky-aeA7XfSqyWDM2m","","");
							if(preg_match("%challenge : '(.*)'%U", $page, $matches)) 
								echo 'captcha code \''.trim($matches[1]).'\'';
							else echo '<font color=blue>'.$url.'</font> <font color=red>==&#9658; Authentication Required, pls contact admin@vinaget.us</font>';
							exit;
						}
						if($k ==$maxacc-1 && stristr($page,'"error":"InvalidLogIn"')) die('<font color=red>Invalid LogIn</font>');
						$cookie = $this->GetCookies($page);
						$this->save_cookies("depositfiles.com",$cookie);
					}
					$page=$this->curl($url,$cookie.';lang_current=en;',$post);
					//echo '<textarea>'.$page.'</textarea>';exit;
					$cookies = $this->GetCookies($page);
					$this->cookie = $cookies;
					if(stristr($page, "You have exceeded the")){
						if($k <$maxacc-1) {
							$cookie = '';
							$this->save_cookies("depositfiles.com","");
							continue;
						}
						else die("<font color=red>Account out of bandwidth</font>");
					}
					elseif(strpos($page,'Please, enter the password for this file')) die($this->lang['reportpass']);
					elseif (preg_match('/ocation: *(.*)/i', $page, $redir))$link = trim($redir[1]);
					elseif (preg_match('%"(http:\/\/.+depositfiles\.com/auth.+)" onClick="%U', $page, $redir2)) $link = trim($redir2[1]);
					elseif (preg_match('%"(http:\/\/.+dfiles\.eu/auth.+)" onClick="%U', $page, $redir2)) $link = trim($redir2[1]);
					elseif(stristr($page, "Such file does not exist")) die(Tools_get::report($Original,"dead"));
					if($link){
						$size_name = Tools_get::size_name($link, $this->cookie);
						$filesize = $size_name[0];
						$filename = $size_name[1];
						break;
					}
					else {
						$cookie = ""; 
						$this->save_cookies("depositfiles.com","");
					}
				}
				
				if($link) break;
			}
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