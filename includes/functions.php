<?php

require_once( SFS_PLUGIN_DIR . '/vendor/autoload.php' );

use Facebook\Facebook;
use SFS\entity\FacebookApi;
use SFS\entity\TwitterApi;
use SFS\entity\FlickrApi;
use SFS\entity\YoutubeApi;
use SFS\entity\SpritesSocialFeed;

function sfs_plugin_path( $path = '' ) {
  return path_join( SFS_PLUGIN_DIR, trim( $path, '/' ) );
}

function sfs_plugin_url( $path = '' ) {
  $url = plugins_url( $path, SFS_PLUGIN );

  if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
    $url = 'https:' . substr( $url, 5 );
  }

  return $url;
}

function sfs_upload_dir( $type = false ) {
  $uploads = wp_get_upload_dir();

  $uploads = apply_filters( 'sfs_upload_dir', array(
    'dir' => $uploads['basedir'],
    'url' => $uploads['baseurl'],
  ) );

  if ( 'dir' == $type ) {
    return $uploads['dir'];
  } if ( 'url' == $type ) {
    return $uploads['url'];
  }

  return $uploads;
}

function sfs_verify_nonce( $nonce, $action = 'wp_rest' ) {
  return wp_verify_nonce( $nonce, $action );
}

function sfs_create_nonce( $action = 'wp_rest' ) {
  return wp_create_nonce( $action );
}

function sfs_blacklist_check( $target ) {
  $mod_keys = trim( get_option( 'blacklist_keys' ) );

  if ( empty( $mod_keys ) ) {
    return false;
  }

  $words = explode( "\n", $mod_keys );

  foreach ( (array) $words as $word ) {
    $word = trim( $word );

    if ( empty( $word ) || 256 < strlen( $word ) ) {
      continue;
    }

    $pattern = sprintf( '#%s#i', preg_quote( $word, '#' ) );

    if ( preg_match( $pattern, $target ) ) {
      return true;
    }
  }

  return false;
}

function sfs_array_flatten( $input ) {
  if ( ! is_array( $input ) ) {
    return array( $input );
  }

  $output = array();

  foreach ( $input as $value ) {
    $output = array_merge( $output, wpcf7_array_flatten( $value ) );
  }

  return $output;
}

function sfs_flat_join( $input ) {
  $input = wpcf7_array_flatten( $input );
  $output = array();

  foreach ( (array) $input as $value ) {
    $output[] = trim( (string) $value );
  }

  return implode( ', ', $output );
}

function sfs_support_html5() {
  return (bool) apply_filters( 'sfs_support_html5', true );
}

function sfs_support_html5_fallback() {
  return (bool) apply_filters( 'sfs_support_html5_fallback', false );
}

function sfs_use_really_simple_captcha() {
  return apply_filters( 'sfs_use_really_simple_captcha',
    SFS_USE_REALLY_SIMPLE_CAPTCHA );
}

function sfs_validate_configuration() {
  return apply_filters( 'sfs_validate_configuration',
    SFS_VALIDATE_CONFIGURATION );
}

function sfs_load_js() {
  return apply_filters( 'sfs_load_js', SFS_LOAD_JS );
}

function sfs_load_css() {
  return apply_filters( 'sfs_load_css', SFS_LOAD_CSS );
}

function sfs_format_atts( $atts ) {
  $html = '';

  $prioritized_atts = array( 'type', 'name', 'value' );

  foreach ( $prioritized_atts as $att ) {
    if ( isset( $atts[$att] ) ) {
      $value = trim( $atts[$att] );
      $html .= sprintf( ' %s="%s"', $att, esc_attr( $value ) );
      unset( $atts[$att] );
    }
  }

  foreach ( $atts as $key => $value ) {
    $key = strtolower( trim( $key ) );

    if ( ! preg_match( '/^[a-z_:][a-z_:.0-9-]*$/', $key ) ) {
      continue;
    }

    $value = trim( $value );

    if ( '' !== $value ) {
      $html .= sprintf( ' %s="%s"', $key, esc_attr( $value ) );
    }
  }

  $html = trim( $html );

  return $html;
}

function sfs_link( $url, $anchor_text, $args = '' ) {
  $defaults = array(
    'id' => '',
    'class' => '',
  );

  $args = wp_parse_args( $args, $defaults );
  $args = array_intersect_key( $args, $defaults );
  $atts = wpcf7_format_atts( $args );

  $link = sprintf( '<a href="%1$s"%3$s>%2$s</a>',
    esc_url( $url ),
    esc_html( $anchor_text ),
    $atts ? ( ' ' . $atts ) : '' );

  return $link;
}

function sfs_get_request_uri() {
  static $request_uri = '';

  if ( empty( $request_uri ) ) {
    $request_uri = add_query_arg( array() );
  }

  return esc_url_raw( $request_uri );
}

function sfs_register_post_types() {
  if ( class_exists( 'SpritesSocialFeed' ) ) {
    SpritesSocialFeed::register_post_type();
    return true;
  } else {
    return false;
  }
}

function sfs_start_cron_jobs() {
  $option_fb = get_option('sfs-fb-credentials');
  $option_yt = get_option('sfs-yt-credentials');
  $option_flickr = get_option('sfs-flickr-credentials');
  $option_twitter = get_option('sfs-twitter-credentials');

  add_action( 'sfs_cron_hook', 'sfs_cron_persist_feed_posts' );
  add_action( 'sfs_cron_hook_secondary', 'sfs_cron_persist_twitter_feed_posts' );
  add_action( 'sfs_cron_hook_events', 'sfs_cron_persist_social_events' );
  add_action( 'sfs_cron_hook_videos', 'sfs_cron_persist_videos' );
  add_action( 'sfs_cron_hook_albums', 'sfs_cron_persist_albums' );

  if ( ! wp_next_scheduled( 'sfs_cron_hook' ) && (isset($option_fb['sfs-enable-service'])) ) {
    if(isset($option_fb['sfs-fb-user-fields']) && ($option_fb['sfs-fb-user-fields'] == 'posts')) {
      wp_schedule_event(time(), 'hourly', 'sfs_cron_hook');
    } else if(isset($option_fb['sfs-fb-user-fields']) && ($option_fb['sfs-fb-user-fields'] == 'events')) {
      wp_schedule_event(time(), 'hourly', 'sfs_cron_hook_events');
    }
  }
  if ( ! wp_next_scheduled( 'sfs_cron_hook_secondary' ) && (isset($option_twitter['sfs-enable-service'])) ) {
    wp_schedule_event( time(), 'hourly', 'sfs_cron_hook_secondary' );
  }
  if ( ! wp_next_scheduled( 'sfs_cron_hook_videos' ) && (isset($option_yt['sfs-enable-service'])) ) {
    wp_schedule_event( time(), 'hourly', 'sfs_cron_hook_videos' );
  }
  if ( ! wp_next_scheduled( 'sfs_cron_hook_albums' ) && (isset($option_flickr['sfs-enable-service'])) ) {
    wp_schedule_event( time(), 'hourly', 'sfs_cron_hook_albums' );
  }
}

function sfs_get_facebook_feed_posts() {
  $option = get_option('sfs-fb-credentials');
  $app_id = $option['sfs-fb-app-id'];
  $app_secret = $option['sfs-fb-app-secret'];
  $page = $option['sfs-fb-user-id'];

  $fb = new Facebook([
    'app_id' => $app_id,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.3',
  ]);
  $api = new FacebookApi();
  $api->setPage($page);
  $api->setFb($fb);
  $api->setFbApp($fb->getApp());
  $api->setAccessToken($api->getFbApp()->getAccessToken());
  $api->sendRequest();

  return (isset($option['sfs-enable-service'])) ? $api->getPublicPosts() : [];
}

function sfs_get_facebook_events() {
  $option = get_option('sfs-fb-credentials');
  $app_id = $option['sfs-fb-app-id'];
  $app_secret = $option['sfs-fb-app-secret'];
  $page = $option['sfs-fb-user-id'];

  $fb = new Facebook([
    'app_id' => $app_id,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.3',
  ]);
  $api = new FacebookApi();
  $api->setPage($page);
  $api->setFb($fb);
  $api->setFbApp($fb->getApp());
  $api->setAccessToken($api->getFbApp()->getAccessToken());
  $api->sendEventRequest();

  return (isset($option['sfs-enable-service'])) ? $api->getPublicEvents() : [];
}

function sfs_get_youtube_videos() {
  $option = get_option('sfs-yt-credentials');
  $api_key = $option['sfs-yt-api-key'];
  $playlist_id = $option['sfs-yt-playlist-id'];
  $max = $option['sfs-yt-max'];

  $yt = new YoutubeApi();
  $yt->setClient(new \Google_Client());
  $yt->setApiKey($api_key);
  $yt->setService(new Google_Service_YouTube($yt->getClient()));

  $videos = $yt->sendRequest($yt->getService(), 'snippet,contentDetails', ['playlistId' => $playlist_id, 'maxResults' => $max]);
  $yt->setVideos($videos);

  return (isset($option['sfs-enable-service'])) ? $yt->getVideos() : [];
}

function sfs_get_flickr_photosets() {
  $option = get_option('sfs-flickr-credentials');
  $api_key = $option['sfs-flickr-api-key'];
  $user_id = $option['sfs-flickr-user'];

  $flickr = new FlickrApi();
  $flickr->setEncodedParams([
    'api_key' => $api_key,
    'method' => 'flickr.photosets.getList',
    'user_id' => $user_id,
    'format' => 'php_serial'
  ]);
  $flickr->sendRequest();

  return (isset($option['sfs-enable-service'])) ? $flickr->getResponse() : [];
}

function sfs_get_flickr_photos($id) {
  $option = get_option('sfs-flickr-credentials');
  $api_key = $option['sfs-flickr-api-key'];
  $user_id = $option['sfs-flickr-user'];

  $flickr = new FlickrApi();
  $flickr->setEncodedParams([
    'api_key' => $api_key,
    'method' => 'flickr.photosets.getPhotos',
    'photoset_id' => $id,
    'user_id' => $user_id,
    'format' => 'php_serial'
  ]);
  $flickr->sendRequest();

  return (isset($option['sfs-enable-service'])) ? $flickr->getResponse() : [];
}

function sfs_get_twitter_feed_posts() {
  $option = get_option('sfs-twitter-credentials');

  $twitter = new TwitterApi();
  $twitter->setApi(new TwitterAPIExchange([
    'oauth_access_token' => $option['sfs-api-oa-token'],
    'oauth_access_token_secret' => $option['sfs-api-oa-token-secret'],
    'consumer_key' => $option['sfs-api-consumer-key'],
    'consumer_secret' => $option['sfs-api-consumer-key-secret']
  ]));
  $twitter->setUrl('https://api.twitter.com/1.1/statuses/user_timeline.json');
  $twitter->setField('?screen_name='.$option['sfs-api-screen-name']);
  $twitter->sendGetRequest();

  return (isset($option['sfs-enable-service'])) ? $twitter->getTweets() : [];
}

function sfs_cron_persist_albums($data) {
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

function sfs_cron_persist_videos($data) {
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

function sfs_cron_persist_feed_posts() {
  $posts = sfs_get_facebook_feed_posts();
  $data = $posts['posts']['data'];
  $global = get_option('sfs-global-options');
  $last_import_date = $global['sfs-last-import-date'];
  foreach ($data as $post) {
    $timestamp = date('Y-m-d H:i:s', strtotime($post['created_time']));
    if(strtotime($timestamp) > strtotime($last_import_date)) {
      $id = sfs_create_feed_post([
        'id' => $post['id'],
        'post_type' => $global['sfs-post-type'],
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
      $global['sfs-last-import-date'] = $timestamp;
      update_option('sfs-global-options', $global);
    }
  }
}

function sfs_cron_persist_twitter_feed_posts($data) {
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

function sfs_cron_persist_social_events($data) {
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

/**
 * Updates post meta for a post. It also automatically deletes or adds the value to field_name if specified
 *
 * @access     protected
 * @param      integer     The post ID for the post we're updating
 * @param      string      The field we're updating/adding/deleting
 * @param      string      [Optional] The value to update/add for field_name. If left blank, data will be deleted.
 * @return     void
 */
function __update_post_meta( $post_id, $field_name, $value = '' )
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

function sfs_create_feed_post($data) {
  return wp_insert_post($data);
}

function sfs_version( $args = '' ) {
  $defaults = array(
    'limit' => -1,
    'only_major' => false );

  $args = wp_parse_args( $args, $defaults );

  if ( $args['only_major'] ) {
    $args['limit'] = 2;
  }

  $args['limit'] = (int) $args['limit'];

  $ver = SFS_PLUGIN_VERSION;
  $ver = strtr( $ver, '_-+', '...' );
  $ver = preg_replace( '/[^0-9.]+/', ".$0.", $ver );
  $ver = preg_replace( '/[.]+/', ".", $ver );
  $ver = trim( $ver, '.' );
  $ver = explode( '.', $ver );

  if ( -1 < $args['limit'] ) {
    $ver = array_slice( $ver, 0, $args['limit'] );
  }

  $ver = implode( '.', $ver );

  return $ver;
}

function sfs_version_grep( $version, array $input ) {
  $pattern = '/^' . preg_quote( (string) $version, '/' ) . '(?:\.|$)/';

  return preg_grep( $pattern, $input );
}

function sfs_enctype_value( $enctype ) {
  $enctype = trim( $enctype );

  if ( empty( $enctype ) ) {
    return '';
  }

  $valid_enctypes = array(
    'application/x-www-form-urlencoded',
    'multipart/form-data',
    'text/plain',
  );

  if ( in_array( $enctype, $valid_enctypes ) ) {
    return $enctype;
  }

  $pattern = '%^enctype="(' . implode( '|', $valid_enctypes ) . ')"$%';

  if ( preg_match( $pattern, $enctype, $matches ) ) {
    return $matches[1]; // for back-compat
  }

  return '';
}

function sfs_rmdir_p( $dir ) {
  if ( is_file( $dir ) ) {
    if ( ! $result = @unlink( $dir ) ) {
      $stat = @stat( $dir );
      $perms = $stat['mode'];
      @chmod( $dir, $perms | 0200 ); // add write for owner

      if ( ! $result = @unlink( $dir ) ) {
        @chmod( $dir, $perms );
      }
    }

    return $result;
  }

  if ( ! is_dir( $dir ) ) {
    return false;
  }

  if ( $handle = @opendir( $dir ) ) {
    while ( false !== ( $file = readdir( $handle ) ) ) {
      if ( $file == "." || $file == ".." ) {
        continue;
      }

      wpcf7_rmdir_p( path_join( $dir, $file ) );
    }

    closedir( $handle );
  }

  return @rmdir( $dir );
}

/* From _http_build_query in wp-includes/functions.php */
function sfs_build_query( $args, $key = '' ) {
  $sep = '&';
  $ret = array();

  foreach ( (array) $args as $k => $v ) {
    $k = urlencode( $k );

    if ( ! empty( $key ) ) {
      $k = $key . '%5B' . $k . '%5D';
    }

    if ( null === $v ) {
      continue;
    } elseif ( false === $v ) {
      $v = '0';
    }

    if ( is_array( $v ) || is_object( $v ) ) {
      array_push( $ret, wpcf7_build_query( $v, $k ) );
    } else {
      array_push( $ret, $k . '=' . urlencode( $v ) );
    }
  }

  return implode( $sep, $ret );
}

/**
 * Returns the number of code units in a string.
 *
 * @see http://www.w3.org/TR/html5/infrastructure.html#code-unit-length
 *
 * @return int|bool The number of code units, or false if mb_convert_encoding is not available.
 */
function sfs_count_code_units( $string ) {
  static $use_mb = null;

  if ( is_null( $use_mb ) ) {
    $use_mb = function_exists( 'mb_convert_encoding' );
  }

  if ( ! $use_mb ) {
    return false;
  }

  $string = (string) $string;
  $string = str_replace( "\r\n", "\n", $string );

  $encoding = mb_detect_encoding( $string, mb_detect_order(), true );

  if ( $encoding ) {
    $string = mb_convert_encoding( $string, 'UTF-16', $encoding );
  } else {
    $string = mb_convert_encoding( $string, 'UTF-16', 'UTF-8' );
  }

  $byte_count = mb_strlen( $string, '8bit' );

  return floor( $byte_count / 2 );
}

function sfs_is_localhost() {
  $server_name = strtolower( $_SERVER['SERVER_NAME'] );
  return in_array( $server_name, array( 'localhost', '127.0.0.1' ) );
}

function sfs_deprecated_function( $function, $version, $replacement ) {
  $trigger_error = apply_filters( 'deprecated_function_trigger_error', true );

  if ( WP_DEBUG && $trigger_error ) {
    if ( function_exists( '__' ) ) {
      trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since Contact Form 7 version %2$s! Use %3$s instead.', 'sfs-feed' ), $function, $version, $replacement ) );
    } else {
      trigger_error( sprintf( '%1$s is <strong>deprecated</strong> since Contact Form 7 version %2$s! Use %3$s instead.', $function, $version, $replacement ) );
    }
  }
}
