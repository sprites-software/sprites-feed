<?php

namespace SFS\entity;

class FlickrApi {

  private $response;

  private $encoded_params;

  public function __construct() {
    $this->photos = [];
    $this->encoded_params = [];
  }
  public function setResponse($response) {
    $this->response = $response;
  }
  public function getResponse() {
    return $this->response;
  }
  public function getEncodedParams() {
    return $this->encoded_params;
  }
  public function setEncodedParams($params){
    foreach($params as $k => $v) {
      $this->encoded_params[] = urlencode($k).'='.urlencode($v);
    }
  }
  public function sendRequest() {
    $url = "https://api.flickr.com/services/rest/?".implode('&', $this->getEncodedParams());
    $rsp = file_get_contents($url);
    $obj = unserialize($rsp);

    $this->setResponse(json_decode(json_encode($obj), true));
  }
}