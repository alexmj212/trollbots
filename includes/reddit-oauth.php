<?php

class RedditOAuth {
	private $reddit_token_url = "https://www.reddit.com/api/v1/access_token";
	private $redirect_uri = "http://slackphpbot-test.winginit.net/subredditbot-redditauth";

	private $client_id = 'zwgLiGWp8XLwMA';
	private $client_secret = 'LMZP8kRagGY_HOJksM6mrNWVMtQ';
	private $username = 'primeradical';
	private $password = '5vz3ICLlcxS0';

	private $access_token;

	private $data = null;

	public function __construct(){

        	$ch = curl_init();
		$fields = "&grant_type=password";
		$fields .= "&username=".$this->username;
		$fields .= "&password=".$this->password;
	        curl_setopt($ch, CURLOPT_URL,$this->reddit_token_url);
		curl_setopt($ch, CURLOPT_POST, 3);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	        curl_setopt($ch, CURLOPT_USERPWD,$this->client_id.':'.$this->client_secret);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        $response = json_decode(curl_exec ($ch));
        	curl_close ($ch);
		$this->access_token = $response->access_token;

	}

	public function getAccessToken(){
		return $this->access_token;
	}

	public function getSubredditData(){
		return $this->data;
	}

	public function requestSubreddit($subreddit){

		$endpoint = "https://oauth.reddit.com/r/$subreddit/about/";
		//$endpoint = "https://oauth.reddit.com/api/v1/me";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$endpoint);
		$header = array();
		$header[] = "Authorization: bearer ".$this->access_token;
                curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
                curl_setopt( $ch, CURLOPT_USERAGENT, "SlackPHPBot/0.1 by primeradical" );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = json_decode(curl_exec ($ch));
		$info = curl_getinfo($ch);
		if($info['http_code'] == 200){
			$this->data = $response->data;
		}
                curl_close ($ch);


	}

}

