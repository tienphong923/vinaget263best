<?php
if (strpos($url, 'letitbit.net')!==false){
	$account = trim($this->get_account('letitbit.net'));

	$data = array(
		'ffCCv3YT',
		array('key/info'),
		array( 'download/direct_links',
			array(
				'link' => $url,
				'pass' => $account
			)
		)
	);

	$ch = curl_init();
	curl_setopt_array($ch, array(
		CURLOPT_SSL_VERIFYPEER	=> false,
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_POST            => true,
		CURLOPT_URL             => 'https://api.letitbit.net/',
		CURLOPT_POSTFIELDS      => array('r' => json_encode($data)),
	));
	$result = curl_exec($ch);

	if (!$result) {
		curl_close($ch);
		die('Curl error: ' . curl_error($ch));
	}

	curl_close($ch);

	$result = @json_decode($result, true);

	if ($result['status'] == 'FAIL') {
		die($result['data']);
	}

	$link = null;
	if (!empty($result)) {
		$link = trim($result['data'][1][0]);
		$link2 = count($result['data'][1])>1 ? trim($result['data'][1][1]) : '';
		$size_name = Tools_get::size_name($link, null);
		if($size_name[0] < 200 && !empty($link2)) 
			$link = $link2;
		else {
			$filesize = $size_name[0];
			$filename = $size_name[1];
		}
	}
}


/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: GioViet @ Share4U.VN
* Updated:
*/
?>