<?php

namespace SFS\entity;

class InstagramApi {

  private $access_key;

  private $api_secret;

  public function setAccessKey($access_key) {
    $this->access_key = $access_key;
  }
  public function getAccessKey() {
    return $this->access_key;
  }
  public function setApiSecret($api_secret) {
    $this->api_secret = $api_secret;
  }
  public function getApiSecret() {
    return $this->api_secret;
  }
  public function sendRequest() {
    return [];
  }
}