<?php

namespace SFS\entity;

use Facebook\FacebookRequest;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class FacebookApi {

  private $fb;

  private $fbApp;

  private $access_token;

  private $public_posts;

  private $public_events;

  private $page;

  public function __construct() {
    $this->public_posts = [];
  }

  public function setAccessToken($accessToken) {
    $this->access_token = $accessToken;
  }

  public function getAccessToken() {
    return $this->access_token;
  }

  public function setFb($fb) {
    $this->fb = $fb;
  }

  public function getFb() {
    return $this->fb;
  }

  public function setFbApp($fbApp) {
    $this->fbApp = $fbApp;
  }

  public function getFbApp() {
    return $this->fbApp;
  }

  public function setPublicPosts($posts) {
    $this->public_posts = $posts;
  }

  public function getPublicPosts() {
    return $this->public_posts;
  }

  public function setPublicEvents($events) {
    $this->public_events = $events;
  }

  public function getPublicEvents() {
    return $this->public_events;
  }

  public function setPage($page) {
    $this->page = $page;
  }

  public function getPage() {
    return $this->page;
  }

  public function sendRequest() {
    $fb = $this->getFb();
    $app = $this->getFbApp();
    $token = $this->getAccessToken();
    $page = $this->getPage();

    $request = new FacebookRequest($app, $token, 'GET', '/'.$page.'/?fields=posts{message,created_time,picture,id,full_picture,type,attachments}');

    // Send the request to Graph
    try {
      $response = $fb->getClient()->sendRequest($request);
    } catch (FacebookResponseException $e) {
      // When Graph returns an error
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(FacebookSDKException $e) {
      // When validation fails or other local issues
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
    $this->setPublicPosts($response->getDecodedBody());
  }

  public function sendEventRequest() {
    $fb = $this->getFb();
    $app = $this->getFbApp();
    $token = $this->getAccessToken();

    $request = new FacebookRequest($app, $token, 'GET', '/MirekPrezident.cz/?fields=events');
    try {
      $response = $fb->getClient()->sendRequest($request);
    } catch (FacebookResponseException $e) {
      echo 'Graph returned an error: ' . $e->getMessage();
      exit;
    } catch(FacebookSDKException $e) {
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
    $this->setPublicEvents($response->getDecodedBody());
  }
}

