<?php
if (preg_match('#^http://([a-z0-9]+)\.upfile\.vn/#', $url) || preg_match('#^http://upfile\.vn/#', $url)){
	$maxacc = count($this->acc['upfile.vn']['accounts']);
	if($maxacc > 0){
		for ($k=0; $k < $maxacc; $k++){
			$account = trim($this->acc['upfile.vn']['accounts'][$k]);
			if (stristr($account,':')) list($user, $pass) = explode(':',$account);
			else $cookie = $account;
			if(empty($cookie)==false || ($user && $pass)){
				for ($j=0; $j < 2; $j++){
					if(!$cookie) $cookie = $this->get_cookie('upfile.vn');
					if(!$cookie){
						$post = 'loginUsername='.$user.'&loginPassword='.$pass.'&submit=%C4%90%C4%83ng+nh%E1%BA%ADp&submitme=1';
						$data = $this->curl('http://upfile.vn/login.html', '', $post);
						$cookie = $this->GetCookies($data);
						if (strpos($cookie, 'spf=')===false)
							die("<font color=red>Account login fail!</font>");
						else
							$this->save_cookies('upfile.vn', $cookie);
					}
					
					$this->cookie = $cookie;
					$link = $url;
					$size_name = Tools_get::size_name($link, $this->cookie);
					if($size_name[0] > 200 ){
						$filesize =  $size_name[0];
						$filename = $size_name[1];
						break;
					}
					else {
						$link='';
						$this->save_cookies('upfile.vn', '');
						die("<font color=red>File not found/ Account expried!</font>");
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