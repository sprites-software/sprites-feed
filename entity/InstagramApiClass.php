<?php

namespace SFS\entity;

class InstagramApi {

  private $api_key;

  private $api_secret;

  public function setApiKey($api_key) {
    $this->api_key = $api_key;
  }
  public function getApiKey() {
    return $this->api_key;
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