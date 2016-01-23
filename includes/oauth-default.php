<?php

class OAuth {
	private $slack_url = 'https://slack.com/api/oauth.access';

	private $client_id;
	private $client_secret;
	private $code;

	public function __construct($data,$botName){

		$this->code = $data['code'];
		$ch = curl_init();
		$url = $this->slack_url.'?client_id='.$this->client_id.'&client_secret='.$this->client_secret.'&code='.$this->code;
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_exec ($ch);
		curl_close ($ch);
		header('Location: http://alexmj212.github.io/slackphpbot/?success='.$botName);
	}

}

