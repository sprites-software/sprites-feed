<?php

namespace SFS\entity;

class YoutubeApi {

  private $api_key;

  private $videos;

  private $service;

  private $client;

  public function __construct() {
    $this->videos = [];
  }
  public function setApiKey($api_key) {
    $this->api_key = $api_key;
  }
  public function getApiKey() {
    return $this->api_key;
  }
  public function setVideos($videos) {
    $this->videos = $videos;
  }
  public function getVideos() {
    return $this->videos;
  }
  public function setService($service) {
    $this->service = $service;
  }
  public function getService() {
    return $this->service;
  }
  public function setClient($client) {
    $this->client = $client;
  }
  public function getClient() {
    return $this->client;
  }
  public function sendRequest($service ,$part, $params){
    $client = $this->getClient();
    $client->setApplicationName('MirekPrezidentAPI');
    $client->setDeveloperKey($this->getApiKey());

    $response = $service->playlistItems->listPlaylistItems(
      $part,
      $params
    );

    return (json_decode(json_encode($response), true));
  }
}