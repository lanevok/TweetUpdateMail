<?php

Class Twitter {
  var $conn;

  public function Twitter(){
    require_once 'twitteroauth/twitteroauth.php';
    $this->conn = new TwitterOAuth(
				   CONSUMER_KEY, CONSUMER_SECRET,
				   ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
  }

  /**
   * 指定したユーザのタイムラインを取得する
   * @param unknown $user 取得するユーザ名(スクリーンネーム)
   * @return Ambigous <mixed, API> タイムライン
   */
  public function userTL($user){
  	$params = array("screen_name" => $user);
  	return $this->conn->get('statuses/user_timeline',$params);
  }

  public function post($tweet_text){
    $params = array('status' => $tweet_text);
    $this->conn->post('statuses/update',$params);
  }
}