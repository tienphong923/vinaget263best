<?php
$account = trim($this->get_account('unrestrict.li'));
if (stristr($account,':')) 
	list($user, $pass) = explode(':',$account);
else 
	$cookie = $account;

if(empty($cookie)==false || ($user && $pass)){
	for ($j=0; $j < 2; $j++){
		if(!$cookie) 
			$cookie = $this->get_cookie("unrestrict.li");

		if (!$cookie) {
			//Login
			$post = 'return=home&username'.$user.'&password='.$pass.'&signin=Sign in';
			

			$data = $this->curl('http://unrestrict.li/sign_in', null, $post);
			if(preg_match('%(unrestrict_user=.+);%U', $data, $cook)){
				$cookie = 'lang=EN; ssl=0; unrestrict_user=' . $cook[1];
				$this->save_cookies('unrestrict.li', $cookie);
			}
		}

		$this->cookie = $cookie;
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0];
			$pass = $linkpass[1];
		}

		
		$post = 'domain=long&link=' . $url;
		$data = $this->curl('http://unrestrict.li/unrestrict.php',$this->cookie,$post,0);


		$js = json_decode($data, true);
		foreach ($js as $key => $value) {
			if (!empty($value['invalid'])) {
				die("<font color=red><b>Unrestrict.li > " . $value['invalid'] ."</b></font>");
			}
			else {
				$link = trim($key);
				$size_name = Tools_get::size_name($link, $this->cookie);
				if($size_name[0] > 200 ){
					$filesize =  $size_name[0];
					$filename = $size_name[1];
					break;
				}
				else $link='';
			}

			break;	//Run Oncce
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