<?php
if (preg_match('#^http://([a-z0-9]+\.)?share\.vnn\.vn/#', $url)){
	$account = trim($this->get_account('share.vnn.vn'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("share.vnn.vn");
			if(!$cookie){
				$data = $this->curl("https://id.vnn.vn/login?service=http%3A%2F%2Fshare.vnn.vn%2Flogin.php%3Fdo%3Dlogin%26url%3Dhttp%253A%252F%252Fshare.vnn.vn%252F", '', '');
				if(preg_match('%JSESSIONID=(.+);%',$data,$match)) $jsid=trim($match[1]); else $jsid='';
				if(preg_match('%lt\" value=\"(.+)"%',$data,$match1)) $lt=trim($match1[1]); else $lt='';
				$cookie = 'JSESSIONID='.$jsid;
				$data = $this->curl('https://id.vnn.vn/login;jsessionid=' .$jsid.'?service=http%3A%2F%2Fshare.vnn.vn%2Flogin.php%3Fdo%3Dlogin%26url%3Dhttp%253A%252F%252Fshare.vnn.vn%252F', $cookie, "username=$user&password=$pass&lt=$lt&_eventId=submit&submit=%C4%90%C4%83ng+nh%E1%BA%ADp");
				$cookies = $this->GetCookies($data);
				if(preg_match("#Location: (.*)#", $data, $match2) )
				{
					$data = $this->curl($match2[1], $cookies, '');
					$cookies = $cookies. '; ' .$this->GetCookies($data);
					if(preg_match('#PHPSESSID=ST(.+)#',$cookies,$match4)) $cookie=$cookie."; ".$match4[0];
					
				}
				$this->save_cookies("share.vnn.vn",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url, $cookie, '');
			if (preg_match("%window.location.href='(.+)'%", $data, $value)) {
				$link = $value[1];
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($data,"File not found")) {
				$this->error("$url <br> The requested file is not found");
				exit;
			}
			else 
			{
				$filesize = -1;
				$filename = '';
				$cookie = '';
				$this->save_cookies('share.vnn.vn', $cookie);
			}
		}
	}
}
?>