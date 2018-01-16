<?php

require_once( SFS_PLUGIN_DIR . '/vendor/autoload.php' );

use Facebook\Facebook;
use SFS\entity\FacebookApi;
use SFS\entity\TwitterApi;
use SFS\entity\FlickrApi;
use SFS\entity\YoutubeApi;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if( ! class_exists('SFSCron') ):
  class SFSCron {

    private $post_type;

    private $last_import_date;

    public function setPostType($post_type) {
      $this->post_type = $post_type;
    }
    public function getPostType() {
      return $this->post_type;
    }
    public function setLastImportDate($last_import_date) {
      $this->last_import_date = $last_import_date;
    }
    public function getLastImportDate() {
      return $this->last_import_date;
    }

    public function __construct(){
      add_action( 'sfs_cron_hook', array($this, 'cron_persist_feed_posts') );
      add_action( 'sfs_cron_hook_secondary', array($this, 'cron_persist_twitter_feed_posts') );
      add_action( 'sfs_cron_hook_events', array($this, 'cron_persist_social_events') );
      add_action( 'sfs_cron_hook_videos', array($this, 'cron_persist_videos') );
      add_action( 'sfs_cron_hook_albums', array($this, 'cron_persist_albums') );
      add_action( 'sfs_cron_hook_people', array($this, 'cron_persist_people_videos') );
    }

    public function schedule_cron_tasks() {
      if ( ! wp_next_scheduled( 'sfs_cron_hook' ) ) {
        wp_schedule_event( time(), 'hourly', 'sfs_cron_hook' );
      }
      if ( ! wp_next_scheduled( 'sfs_cron_hook_secondary' ) ) {
        wp_schedule_event( time(), 'hourly', 'sfs_cron_hook_secondary' );
      }
      if ( ! wp_next_scheduled( 'sfs_cron_hook_events' ) ) {
        wp_schedule_event( time(), 'hourly', 'sfs_cron_hook_events' );
      }
      if ( ! wp_next_scheduled( 'sfs_cron_hook_videos' ) ) {
        wp_schedule_event( time(), 'hourly', 'sfs_cron_hook_videos' );
      }
      if ( ! wp_next_scheduled( 'sfs_cron_hook_albums' ) ) {
        wp_schedule_event( time(), 'hourly', 'sfs_cron_hook_albums' );
      }
      if ( ! wp_next_scheduled( 'sfs_cron_hook_people' ) ) {
        wp_schedule_event( time(), 'hourly', 'sfs_cron_hook_people' );
      }
    }

    public function get_facebook_feed_posts($app_id, $app_secret)
    {
      $fb = new Facebook([
        'app_id' => $app_id,
        'app_secret' => $app_secret,
        'default_graph_version' => 'v2.3',
      ]);
      $api = new FacebookApi();
      $api->setFb($fb);
      $api->setFbApp($fb->getApp());
      $api->setAccessToken($api->getFbApp()->getAccessToken());
      $api->sendRequest();

      return $api->getPublicPosts();
    }

    public function get_facebook_events($app_id, $app_secret)
    {
      $fb = new Facebook([
        'app_id' => $app_id,
        'app_secret' => $app_secret,
        'default_graph_version' => 'v2.3',
      ]);
      $api = new FacebookApi();
      $api->setFb($fb);
      $api->setFbApp($fb->getApp());
      $api->setAccessToken($api->getFbApp()->getAccessToken());
      $api->sendEventRequest();

      return $api->getPublicEvents();
    }

    public function get_youtube_videos($api_key, $playlist_id, $max)
    {
      $yt = new YoutubeApi();
      $yt->setClient(new \Google_Client());
      $yt->setApiKey($api_key);
      $yt->setService(new Google_Service_YouTube($yt->getClient()));

      $videos = $yt->sendRequest($yt->getService(), 'snippet,contentDetails', ['playlistId' => $playlist_id, 'maxResults' => $max]);
      $yt->setVideos($videos);

      return $yt->getVideos();
    }

    public function get_flickr_photosets($api_key, $user_id)
    {
      $flickr = new FlickrApi();
      $flickr->setEncodedParams([
        'api_key' => $api_key,
        'method' => 'flickr.photosets.getList',
        'user_id' => $user_id,
        'format' => 'php_serial'
      ]);
      $flickr->sendRequest();
      return $flickr->getResponse();
    }

    public function get_flickr_photos($api_key, $id, $user_id)
    {
      $flickr = new FlickrApi();
      $flickr->setEncodedParams([
        'api_key' => $api_key,
        'method' => 'flickr.photosets.getPhotos',
        'photoset_id' => $id,
        'user_id' => $user_id,
        'format' => 'php_serial'
      ]);
      $flickr->sendRequest();
      return $flickr->getResponse();
    }

    public function get_twitter_feed_posts($oa_token, $token_secret, $consumer_key, $consumer_secret, $screen_name)
    {
      $twitter = new TwitterApi();
      $twitter->setApi(new TwitterAPIExchange([
        'oauth_access_token' => $oa_token,
        'oauth_access_token_secret' => $token_secret,
        'consumer_key' => $consumer_key,
        'consumer_secret' => $consumer_secret
      ]));
      $twitter->setUrl('https://api.twitter.com/1.1/statuses/user_timeline.json');
      $twitter->setField('?screen_name='.$screen_name);
      $twitter->sendGetRequest();

      return $twitter->getTweets();
    }

    public function cron_persist_albums($data) {
      $last_import_date = $this->getLastImportDate();
      foreach($data as $gallery) {
        $timestamp = gmdate('Y-m-d H:i:s', $gallery['date_create']);
        if(strtotime($timestamp) > strtotime($last_import_date)) {
          $photos = $this->get_flickr_photos($gallery['id']);
          $id = $this->create_feed_post([
            'id' => $gallery['id'],
            'post_type' => 'feed',
            'post_title' => 'flickr-post-' . date('d-M-Y', strtotime($timestamp)),
            'post_content' => $gallery['title']['_content'],
            'post_status' => 'publish',
            'post_date' => date('Y-m-d H:i:s', strtotime($timestamp))
          ]);
  //        $this->__update_post_meta($id, 'feed_type', 'flickr');
  //        $this->__update_post_meta($id, 'feed_link', 'https://flickr.com/photos/159828506@N05/albums/'.$gallery['id']);
  //        $this->__update_post_meta($id, 'feed_picture', 'https://farm'.$photos['photoset']['photo'][0]['farm'].'.staticflickr.com/'.$photos['photoset']['photo'][0]['server'].'/'.$photos['photoset']['photo'][0]['id'].'_'.$photos['photoset']['photo'][0]['secret'].'_z.jpg');
  //        $this->__update_post_meta($id, 'feed_isVideo', false);
        }
      }
    }

    public function cron_persist_videos($data) {
      $last_import_date = $this->getLastImportDate();
      foreach ($data as $post) {
        $data = json_decode(json_encode($post), true);
        $timestamp = date('Y-m-d H:i:s', strtotime($data['contentDetails']['videoPublishedAt']));
        if((strtotime($timestamp) > strtotime($last_import_date)) && ($data['snippet']['title'] != 'Private video')) {
          $id = $this->create_feed_post([
            'id' => $data['id'],
            'post_type' => 'feed',
            'post_title' => 'youtube-post-' . date('d-M-Y', strtotime($data['contentDetails']['videoPublishedAt'])),
            'post_content' => $data['snippet']['title'],
            'post_status' => 'publish',
            'post_date' => date('Y-m-d H:i:s', strtotime($data['contentDetails']['videoPublishedAt']))
          ]);
  //        $this->__update_post_meta($id, 'feed_type', 'youtube');
  //        $this->__update_post_meta($id, 'feed_link', '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$data['snippet']['resourceId']['videoId'].'" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>');
  //        $this->__update_post_meta($id, 'feed_picture', $data['snippet']['thumbnails']['medium']['url']);
  //        $this->__update_post_meta($id, 'feed_isVideo', true);
        }
      }
    }

    public function cron_persist_feed_posts($data) {
      $last_import_date = $this->getLastImportDate();
      foreach ($data as $post) {
        $timestamp = date('Y-m-d H:i:s', strtotime($post['created_time']));
        if(strtotime($timestamp) > strtotime($last_import_date)) {
          $id = $this->create_feed_post([
            'id' => $post['id'],
            'post_type' => $this->getPostType(),
            'post_title' => 'facebook-post-' . date('d-M-Y', strtotime($post['created_time'])),
            'post_content' => $post['message'],
            'post_status' => 'publish',
            'post_date' => $post['created_time']
          ]);
  //        $this->__update_post_meta($id, 'feed_type', 'facebook');
  //        $this->__update_post_meta($id, 'feed_link', ($post['attachments']['data'][0]['target']) ? $post['attachments']['data'][0]['target']['url'] : '');
  //        $this->__update_post_meta($id, 'feed_picture', ($post['attachments']['data'][0]['media']['image']) ? $post['attachments']['data'][0]['media']['image']['src'] : '');

          if($post['type'] == 'video') {
  //          $this->__update_post_meta($id, 'feed_isVideo', true);
          }
        }
      }
    }
    public function cron_persist_twitter_feed_posts($data) {
      $last_import_date = $this->getLastImportDate();
      foreach ($data as $post) {
        $data = json_decode(json_encode($post), true);
        $date = date('Y-m-d H:i:s', strtotime($data['created_at']));
        if((strtotime($date) > strtotime($last_import_date)) && !(preg_match('/^RT/', $data['text']))) {
          $id = $this->create_feed_post([
            'id' => $data['id'],
            'post_type' => $this->getPostType(),
            'post_title' => 'twitter-post-' . date('d-M-Y', strtotime($data['created_at'])),
            'post_content' => $data['text'],
            'post_status' => 'publish',
            'post_date' => date('Y-m-d H:i:s', strtotime($data['created_at']))
          ]);
  //        $this->__update_post_meta($id, 'feed_type', 'twitter');
  //        $this->__update_post_meta($id, 'feed_link', 'https://twitter.com/MirekTopolanek/status/'.$data['id_str']);
          if (isset($data['entities']['media'])) {
  //          $this->__update_post_meta($id, 'feed_picture', $data['entities']['media'][0]['media_url']);
          }
        }
      }
    }
    public function cron_persist_social_events($data) {
      $last_import_date = $this->getLastImportDate();
      foreach ($data as $post) {
        $timestamp = date('Y-m-d H:i:s', strtotime($post['start_time']));
        if(strtotime($timestamp) > strtotime($last_import_date)) {
          $id = $this->create_feed_post([
            'id' => $post['id'],
            'post_type' => $this->getPostType(),
            'post_title' => $post['name'],
            'post_content' => $post['description'],
            'post_status' => 'publish',
            'post_date' => (strtotime($timestamp) > strtotime(date('Y-m-d H:i:s'))) ? '' : $post['start_time']
          ]);
  //        $this->__update_post_meta($id, 'event_link', 'https://facebook.com/events/'.$post['id']);
  //        $this->__update_post_meta($id, 'event_date', date('d-m-Y H:i:s', strtotime($post['start_time'])));
  //        $this->__update_post_meta($id, 'event_address', $post['place']['name']);
        }
      }
    }

    public function cron_persist($data, $type) {
      switch($type) {
        case 'facebook_events':
          $this->cron_persist_social_events($data);
          break;
        case 'facebook_posts':
          $this->cron_persist_feed_posts($data);
          break;
        case 'flickr_photosets':
          $this->cron_persist_albums($data);
          break;
        case 'youtube_playlist':
          $this->cron_persist_videos($data);
          break;
        case 'twitter_timeline':
          $this->cron_persist_twitter_feed_posts($data);
          break;
        case 'instagram_photos':
          break;
        default:
          break;
      }
    }

    /**
     * Updates post meta for a post. It also automatically deletes or adds the value to field_name if specified
     *
     * @access     protected
     * @param      integer     The post ID for the post we're updating
     * @param      string      The field we're updating/adding/deleting
     * @param      string      [Optional] The value to update/add for field_name. If left blank, data will be deleted.
     * @return     void
     */
    public function __update_post_meta( $post_id, $field_name, $value = '' )
    {
      if ( empty( $value ) OR ! $value )
      {
        delete_post_meta( $post_id, $field_name );
      }
      elseif ( ! get_post_meta( $post_id, $field_name ) )
      {
        add_post_meta( $post_id, $field_name, $value );
      }
      else
      {
        update_post_meta( $post_id, $field_name, $value );
      }
    }
    public function create_feed_post($data) {
      return wp_insert_post($data);
    }
  }
endif;