<?php
	if (preg_match('#^http://(www\.)?uploaded\.net/#', $url)){
	$maxacc = count($this->acc['uploaded.to']['accounts']);
	if($maxacc > 0){
		for ($k=0; $k < $maxacc; $k++){
			$account = trim($this->acc['uploaded.to']['accounts'][$k]);
			if (stristr($account,':')) list($user, $pass) = explode(':',$account);
			else $cookie = $account;
			if(empty($cookie)==false || ($user && $pass)){
				for ($j=0; $j < 2; $j++){
					if(!$cookie) $cookie = $this->get_cookie("uploaded.net");
					if(!$cookie){
						$data = $this->curl("http://uploaded.net/io/login",'',"id=$user&pw=$pass");
						$cookie = $this->GetCookies($data);
						$this->save_cookies("uploaded.net",$cookie);
					}
					$this->cookie = $cookie;
					// begin check bw
					/*
					$databw = $this->curl("uploaded.net",$this->cookie,""); 
					$infoacc = $this->cut_str($databw, "For downloading", "</th>");
					if(preg_match('/em class="cB"\>([0-9]{1,5}(,[0-9]{1,5})?) GB\<\/em/i', $infoacc, $matches)) $bw = $matches[1];
					else $bw = 0;
					$bw = strtr($bw, ',', '.');
					if (!is_numeric($bw)) $bw = 0;
					if($bw < 1) {
						$cookie = "";
						$this->save_cookies("uploaded.net","");
						continue;
					}
					*/
					// end check bw
					$data = $this->curl($url,$cookie,"");
					//echo '<textarea>'.$data.'</textarea>';exit;
					if (stristr($data,"<h1>Extend traffic</h1>")) {
						if($k <$maxacc-1) {
							$cookie = '';
							$this->save_cookies("uploaded.net","");
							break;
						}
						else die("<font color=red>Your account out of bandwidth.</font>");
					}
					elseif(preg_match('/ocation: *(.*)/i', $data, $redir)) $link = trim($redir[1]);	
					elseif(preg_match('%(http:\/\/.+uploaded\.net/dl/.+)"%U', $data, $redir2)) $link = trim($redir2[1]);
					elseif (stristr($data,"Our service is currently unavailable in your country")) {
						echo "<font color=red>Our service is currently unavailable in your country.</font>";
						exit;
					}
					elseif (stristr($data,"Download Blocked (ip)")) {
						echo "<font color=red>Download Blocked (ip).</font>";
						exit;
					}
					else {
						$cookie = "";
						$this->save_cookies("uploaded.net","");
					}
					if($link) {
						if (stristr($link,'uploaded.net/404')) die(Tools_get::report($Original,"dead"));
						$size_name = Tools_get::size_name($link, $this->cookie);
						if($size_name[0] > 200 ){
							$filesize =  $size_name[0];
							$filename = $size_name[1];
							break;
						}
						else $link='';
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