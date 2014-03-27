<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?youtube\.com/#', $url)){
	for ($j=0; $j < 2; $j++){
		$data = $this->curl($url,"","");
		if(strstr($data,'verify-age-thumb')) $report = Tools_get::report($url,"Adult");
		elseif(strstr($data,'das_captcha')) $report = Tools_get::report($url,"youtube_captcha");
		elseif(!preg_match('/stream_map=(.[^&]*?)&/i',$data,$match)) $report = Tools_get::report($url,"ErrorLocating");
		elseif(preg_match('/stream_map=(.[^&]*?)&/i',$data,$match)) {
			$this->max_size = $this->max_size_other_host;
			$fmt_url =  urldecode($match[1]);
			if(preg_match('/^(.*?)\\\\u0026/',$fmt_url,$match)) $fmt_url = $match[1];
			$urls = explode(',',$fmt_url);
			$foundArray = array();
			foreach($urls as $urldl){
				if(preg_match('/url=(.*?)&.*?itag=([0-9]+)/si',$urldl,$um)){
					$u = urldecode($um[1]);
					$foundArray[$um[2]] = trim($u);
				}
			}
			if(preg_match('<meta name="title" content="(.*?)">', $data, $matches)) $title = $matches[1];
			else $title = "unknown";

			if (isset($foundArray[13])) {
				$URL=$foundArray[13];
				$filename = $title . ".3gp";
			}
			if (isset($foundArray[17])) {
				$URL=$foundArray[17];
				$filename = $title . ".3gp";
			}
			if (isset($foundArray[36])) {
				$URL=$foundArray[36];
				$filename = $title . ".3gp";
			}
			if (isset($foundArray[5])) {
				$URL=$foundArray[5];
				$filename = $title . ".flv";
			}
			if (isset($foundArray[6])) {
				$URL=$foundArray[6];
				$filename = $title . ".flv";
			}
			if (isset($foundArray[34])) {
				$URL=$foundArray[34];
				$filename = $title . ".flv";
			}
			if (isset($foundArray[35])) {
				$URL=$foundArray[35];
				$filename = $title . ".flv";
			}
			if (isset($foundArray[18])) {
				$URL=$foundArray[18];
				$filename = $title . ".mp4";
			}
			if (isset($foundArray[22])) {
				$URL=$foundArray[22];
				$filename = $title . ".mp4";
			}
			if (isset($foundArray[37])) {
				$URL=$foundArray[37];
				$filename = $title . ".mp4";
			}
			if($URL) {
				$size_name = Tools_get::size_name(trim($URL), "");
				if($size_name[0] > 200 ){
					$filesize = $size_name[0];
					$link = $URL;
					break;
				}
			}
		}
	}
}
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: afterburnerleech.com (7 Sep 2011)
* Updated:
		- By H (Wednesday, November 16, 2011)
		- By H (Thursday, February 02, 2012)

*/
?>