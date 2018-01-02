<?php

namespace SFS\entity;

class TwitterApi {

  private $url;

  private $field;

  private $tweets;

  private $api;

  public function setUrl($url) {
    $this->url = $url;
  }
  public function getUrl() {
    return $this->url;
  }
  public function setField($field) {
    $this->field = $field;
  }
  public function getField() {
    return $this->field;
  }
  public function setTweets($tweets) {
    $this->tweets = $tweets;
  }
  public function getTweets() {
    return $this->tweets;
  }
  public function setApi($api) {
    $this->api = $api;
  }
  public function getApi() {
    return $this->api;
  }

  public function sendGetRequest(){
    $url = $this->getUrl();
    $field = $this->getField();
    $twitter = $this->getApi();
    $response = $twitter->setGetfield($field)
      ->buildOauth($url, 'GET')
      ->performRequest();

    $this->setTweets(json_decode($response));
  }
}