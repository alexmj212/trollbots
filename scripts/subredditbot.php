<?php

class SubredditBot {

	private $botName = 'Subreddit Bot';

	private $botIcon = ':bangbang:';

	public function __construct($data){

		$payload = new ProcessPayload($data);

		$redditauth = new RedditOAuth();

		$redditauth->requestSubreddit($payload->getPayloadText());

		$subredditData = $redditauth->getSubredditData();

		if(!$subredditData){
			$responder = new Responder($this->botName, $this->botIcon, "Not a valid subreddit", $payload->getChannelName(), 0);
			return;
		}

		$attachment = array(
				"fallback" => $subredditData->public_description,
				"text" => $subredditData->public_description,
				"title" => '/r/'.$subredditData->display_name,
				"title_link" => "https://www.reddit.com".$subredditData->url,
				"thumb_url" => $subredditData->header_img,
				"fields" => array(
					array("title" => "Subscribers",
						"value" => number_format($subredditData->subscribers),
						"short" => true),
                                        array("title" => "Active Users",
                                                "value" => number_format($subredditData->accounts_active),
                                                "short" => true),
                                        array("title" => "Created",
                                                "value" => gmdate("F j, Y",$subredditData->created),
                                                "short" => true),

				)
			);

		$responder = new Responder($this->botName, $this->botIcon, $attachment, $payload->getChannelName(), 1);

	}
}
?>
