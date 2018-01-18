<?php

//require_once SFS_PLUGIN_DIR . '/admin/includes/admin-functions.php';
//require_once SFS_PLUGIN_DIR . '/admin/includes/SFSHelpTabs.php';
//require_once SFS_PLUGIN_DIR . '/admin/includes/tag-generator.php';
require_once SFS_PLUGIN_DIR . '/admin/includes/SFSActivationPanel.php';

add_action( 'admin_init', 'sfs_admin_init' );

function sfs_admin_init() {
  do_action( 'sfs_admin_init' );
}

add_action('admin_init', 'sfs_global_options');

function sfs_global_options() {
	$post_types = get_post_types([
		'public' => true
	]);
	register_setting('sfs-global', 'sfs-global-options');
	add_settings_section('sfs-global-settings', __('Global Settings', 'sfs-feed'), null, 'sfs-feed');
	add_settings_field('sfs-last-import-date', __('Last import date', 'sfs-feed'), 'sfs_render_global_setting', 'sfs-feed', 'sfs-global-settings', ['label_for' => 'sfs-last-import-date', 'default_value' => '0']);
	add_settings_field('sfs-post-type', __('Add to post type', 'sfs-feed'), 'sfs_render_post_setting', 'sfs-feed', 'sfs-global-settings', ['label_for' => 'sfs-post-type', 'post_types' => $post_types]);
}

add_action('admin_init', 'sfs_admin_fb_options');

function sfs_admin_fb_options() {
	$fields = [
		'posts' => 'Posts',
	    'events' => 'Events',
	    'photos' => 'Photos'
	];
	// register a new setting for "sfs" page
	register_setting( 'sfs-option-group', 'sfs-fb-credentials' );
	// register a new section in the "sfs-feed-fb-settings" page
	add_settings_section('sfs-section-fb-app', __( 'Facebook App settings', 'sfs-feed' ), 'sfs_render_settings_section', 'sfs-feed-fb-settings');
	add_settings_field('sfs-fb-app-id', __( 'App ID', 'sfs-feed' ), 'sfs_render_fb_settings_field', 'sfs-feed-fb-settings', 'sfs-section-fb-app', ['label_for' => 'sfs-fb-app-id']);
	add_settings_field('sfs-fb-app-secret', __( 'App Secret', 'sfs-feed' ), 'sfs_render_fb_settings_field', 'sfs-feed-fb-settings', 'sfs-section-fb-app', ['label_for' => 'sfs-fb-app-secret']);
	add_settings_section('sfs-section-fb-config', __( 'Facebook API Config', 'sfs-feed' ), 'sfs_render_secondary_settings_section', 'sfs-feed-fb-settings');
	add_settings_field('sfs-fb-user-id', __( 'Your Page/User ID', 'sfs-feed' ), 'sfs_render_fb_settings_field', 'sfs-feed-fb-settings', 'sfs-section-fb-config', ['label_for' => 'sfs-fb-user-id']);
	add_settings_field('sfs-fb-user-fields', __( 'Fields', 'sfs-feed' ), 'sfs_render_fb_settings_fields', 'sfs-feed-fb-settings', 'sfs-section-fb-config', ['label_for' => 'sfs-fb-user-fields', 'fields' => $fields]);
}

add_action('admin_init', 'sfs_admin_yt_options');

function sfs_admin_yt_options() {
  register_setting( 'sfs-yt-option-group', 'sfs-yt-credentials' );
  add_settings_section('sfs-section-yt-app', __( 'Youtube API settings', 'sfs-feed' ), 'sfs_render_settings_section', 'sfs-feed-yt-settings');
  add_settings_field('sfs-yt-api-key', __( 'Api Key', 'sfs-feed' ), 'sfs_render_yt_settings_field', 'sfs-feed-yt-settings', 'sfs-section-yt-app', ['label_for' => 'sfs-yt-api-key']);
  add_settings_section('sfs-section-yt-config', __( 'Youtube API configuration', 'sfs-feed' ), 'sfs_render_secondary_settings_section', 'sfs-feed-yt-settings');
  add_settings_field('sfs-yt-playlist-id', __( 'Playlist ID', 'sfs-feed' ), 'sfs_render_yt_settings_field', 'sfs-feed-yt-settings', 'sfs-section-yt-config', ['label_for' => 'sfs-yt-playlist-id']);
  add_settings_field('sfs-yt-max', __( 'Max results', 'sfs-feed' ), 'sfs_render_yt_settings_number', 'sfs-feed-yt-settings', 'sfs-section-yt-config', ['label_for' => 'sfs-yt-max']);
}

add_action('admin_init', 'sfs_admin_flickr_options');

function sfs_admin_flickr_options() {
	$methods = [
		'flickr.photosets.getList' => 'Photosets',
        'flickr.galleries.getList' => 'Galleries',
	    'flickr.photos.getList' => 'All Photos'
	];

	register_setting( 'sfs-flickr-option-group', 'sfs-flickr-credentials' );
	add_settings_section('sfs-section-flickr-app', __( 'Flickr App settings', 'sfs-feed' ), 'sfs_render_settings_section', 'sfs-feed-flickr-settings');
	add_settings_field('sfs-flickr-api-key', __( 'API Key', 'sfs-feed' ), 'sfs_render_flickr_settings_field', 'sfs-feed-flickr-settings', 'sfs-section-flickr-app', ['label_for' => 'sfs-flickr-api-key']);
	add_settings_field('sfs-flickr-api-secret', __( 'API Secret', 'sfs-feed' ), 'sfs_render_flickr_settings_field', 'sfs-feed-flickr-settings', 'sfs-section-flickr-app', ['label_for' => 'sfs-flickr-api-secret']);
	add_settings_section('sfs-section-flickr-config', __( 'API Configuration', 'sfs-feed' ), 'sfs_render_secondary_settings_section', 'sfs-feed-flickr-settings');
	add_settings_field('sfs-flickr-user', __( 'User ID', 'sfs-feed' ), 'sfs_render_flickr_settings_field', 'sfs-feed-flickr-settings', 'sfs-section-flickr-config', ['label_for' => 'sfs-flickr-user']);
	add_settings_field('sfs-flickr-api-method', __( 'API Method', 'sfs-feed' ), 'sfs_render_flickr_method_field', 'sfs-feed-flickr-settings', 'sfs-section-flickr-config', ['label_for' => 'sfs-flickr-api-method', 'methods' => $methods]);
}

add_action('admin_init', 'sfs_admin_twitter_options');

function sfs_admin_twitter_options() {
  register_setting( 'sfs-twitter-option-group', 'sfs-twitter-credentials' );
  add_settings_section('sfs-section-api-key', __( 'Twitter API Auth', 'sfs-feed' ), 'sfs_render_settings_section', 'sfs-feed-twitter-settings');
  add_settings_field('sfs-api-oa-token', __( 'API OAuth Token', 'sfs-feed' ),'sfs_render_settings_field','sfs-feed-twitter-settings', 'sfs-section-api-key', ['label_for' => 'sfs-api-oa-token']);
  add_settings_field('sfs-api-oa-token-secret', __( 'API OAuth Token Secret', 'sfs-feed' ), 'sfs_render_settings_field', 'sfs-feed-twitter-settings', 'sfs-section-api-key', ['label_for' => 'sfs-api-oa-token-secret']);
  add_settings_field('sfs-api-oa-consumer-key', __( 'API Consumer Key', 'sfs-feed' ), 'sfs_render_settings_field', 'sfs-feed-twitter-settings', 'sfs-section-api-key', ['label_for' => 'sfs-api-oa-consumer-key']);
  add_settings_field('sfs-api-oa-consumer-key-secret', __( 'API Consumer Key Secret', 'sfs-feed' ), 'sfs_render_settings_field', 'sfs-feed-twitter-settings', 'sfs-section-api-key', ['label_for' => 'sfs-api-oa-consumer-key-secret']);
  add_settings_section('sfs-section-api-config', __( 'Twitter API Configuration', 'sfs-feed' ), 'sfs_render_secondary_settings_section', 'sfs-feed-twitter-settings');
  add_settings_field('sfs-api-screen-name', __( 'Twitter screen name', 'sfs-feed' ),'sfs_render_settings_field','sfs-feed-twitter-settings', 'sfs-section-api-config', ['label_for' => 'sfs-api-screen-name']);
}

function sfs_render_settings_section( $args ) {
  ?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Configure your application access keys and configure API calls', 'sfs-feed' ); ?></p>
  <?php
}

function sfs_render_secondary_settings_section( $args ) {
  ?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Configure what should be included in the response', 'sfs-feed' ); ?></p>
  <?php
}
function sfs_render_post_setting($args) {
  $options = get_option( 'sfs-global-options' );
  ?>
	<div class="form-group">
		<select id="<?php echo esc_attr( $args['label_for'] ); ?>" name="sfs-global-options[<?php echo esc_attr($args['label_for']); ?>]">
          <?php foreach($args['post_types'] as $k => $v) : ?>
			  <option value="<?php echo $k; ?>" <?php isset( $options[ esc_attr($args['label_for']) ] ) ? ( selected( $options[ $args['label_for'] ], $k, true ) ) : ( '' ); ?>>
                <?php esc_html_e( $v, 'sfs-feed' ); ?>
			  </option>
          <?php endforeach; ?>
		</select>
	</div>
  <?php
}
function sfs_render_global_setting($args) {
  $options = get_option('sfs-global-options');
  $value = (isset($options[esc_attr($args['label_for'])])) ? $options[esc_attr($args['label_for'])] : 0;
  ?>
    <div class="form-group">
	    <input readonly type="text"
	           id="<?php echo esc_attr($args['label_for']); ?>"
	           name="sfs-global-options[<?php echo esc_attr($args['label_for']); ?>]"
	           value="<?php echo $value; ?>"
	    >
    </div>
  <?php
}
function sfs_render_settings_field($args){
  $options = get_option('sfs-twitter-credentials');
  $value = (isset($options[esc_attr($args['label_for'])])) ? $options[esc_attr($args['label_for'])] : '';
  // output the field
  ?>
	<div class="form-group">
		<input type="text"
		       id="<?php echo esc_attr($args['label_for']); ?>"
		       name="sfs-twitter-credentials[<?php echo esc_attr($args['label_for']); ?>]"
		       value="<?php echo $value; ?>"
		>
	</div>
  <?php
}

function sfs_render_fb_settings_field($args) {
  $options = get_option( 'sfs-fb-credentials' );
  $value = (isset($options[esc_attr($args['label_for'])])) ? $options[esc_attr($args['label_for'])] : '';
  // output the field
  ?>
  <div class="form-group">
	  <input type="text"
	         id="<?php echo esc_attr( $args['label_for'] ); ?>"
	         name="sfs-fb-credentials[<?php echo esc_attr($args['label_for']); ?>]"
	         value="<?php echo $value; ?>"
	  >
  </div>
  <?php
}
function sfs_render_fb_settings_fields($args) {
  $options = get_option( 'sfs-fb-credentials' );
  ?>
	<div class="form-group">
		<select id="<?php echo esc_attr( $args['label_for'] ); ?>" name="sfs-fb-credentials[<?php echo esc_attr($args['label_for']); ?>]">
          <?php foreach($args['fields'] as $k => $v) : ?>
			  <option value="<?php echo $k; ?>" <?php isset( $options[ esc_attr($args['label_for']) ] ) ? ( selected( $options[ $args['label_for'] ], $k, true ) ) : ( '' ); ?>>
                <?php esc_html_e( $v, 'sfs-feed' ); ?>
			  </option>
          <?php endforeach; ?>
		</select>
	</div>
  <?php
}
function sfs_render_flickr_settings_field($args) {
  $options = get_option( 'sfs-flickr-credentials' );
  $value = (isset($options[esc_attr($args['label_for'])])) ? $options[esc_attr($args['label_for'])] : '';
  // output the field
  ?>
  <div class="form-group">
	  <input type="text"
	         id="<?php echo esc_attr( $args['label_for'] ); ?>"
	         name="sfs-flickr-credentials[<?php echo esc_attr($args['label_for']); ?>]"
	         value="<?php echo $value; ?>"
	  >
  </div>
  <?php
}
function sfs_render_flickr_method_field($args) {
  $options = get_option( 'sfs-flickr-credentials' );
  ?>

  <div class="form-group">
	  <select id="<?php echo esc_attr( $args['label_for'] ); ?>" name="sfs-flickr-credentials[<?php echo esc_attr($args['label_for']); ?>]">
	    <?php foreach($args['methods'] as $k => $v) : ?>
		    <option value="<?php echo $k; ?>" <?php isset( $options[ esc_attr($args['label_for']) ] ) ? ( selected( $options[ $args['label_for'] ], $k, true ) ) : ( '' ); ?>>
	          <?php esc_html_e( $v, 'sfs-feed' ); ?>
		    </option>
		<?php endforeach; ?>
	  </select>
  </div>

  <?php
}
function sfs_render_yt_settings_field($args) {
  $options = get_option( 'sfs-yt-credentials' );
  $value = (isset($options[esc_attr($args['label_for'])])) ? $options[esc_attr($args['label_for'])] : '';
  // output the field
  ?>
  <div class="form-group">
	  <input type="text"
	         id="<?php echo esc_attr( $args['label_for'] ); ?>"
	         name="sfs-yt-credentials[<?php echo esc_attr($args['label_for']); ?>]"
	         value="<?php echo $value; ?>"
	  >
  </div>
  <?php
}
function sfs_render_yt_settings_number($args) {
  $options = get_option( 'sfs-yt-credentials' );
  $value = (isset($options[esc_attr($args['label_for'])])) ? $options[esc_attr($args['label_for'])] : '';
  // output the field
  ?>
  <div class="form-group">
	  <input type="number"
	         max="50"
	         min="1"
	         id="<?php echo esc_attr( $args['label_for'] ); ?>"
	         name="sfs-yt-credentials[<?php echo esc_attr($args['label_for']); ?>]"
	         value="<?php echo $value; ?>"
	  >
  </div>
  <?php
}

add_action( 'admin_menu', 'sfs_admin_menu', 9 );

function sfs_admin_menu() {

  add_menu_page( __( 'Social Feed', 'sfs-feed' ), __( 'Social Feed', 'sfs-feed' ), 'sfs_full_capability', 'sfs-feed', 'sfs_admin_global_settings_page', 'dashicons-admin-site', 1000 );

  $settings = add_submenu_page( 'sfs-feed', __( 'Global Settings', 'sfs-feed' ), __( 'Settings', 'sfs-feed' ), 'sfs_full_capability', 'sfs-feed', 'sfs_admin_global_settings_page' );

  add_action( 'load-' . $settings, 'sfs_load_page_admin' );

  $fb = add_submenu_page( 'sfs-feed', __( 'Facebook Settings', 'sfs-feed' ), __( 'Facebook', 'sfs-feed' ), 'sfs_full_capability', 'sfs-feed-fb-settings', 'sfs_admin_fb_settings_page' );

  add_action( 'load-' . $fb, 'sfs_load_page_admin' );

  $twitter = add_submenu_page( 'sfs-feed', __( 'Twitter Settings', 'sfs-feed' ), __( 'Twitter', 'sfs-feed' ), 'sfs_full_capability', 'sfs-feed-twitter-settings', 'sfs_admin_twitter_settings_page' );

  add_action( 'load-' . $twitter, 'sfs_load_page_admin' );

  $flickr = add_submenu_page( 'sfs-feed', __( 'Flickr Settings', 'sfs-feed' ), __( 'Flickr', 'sfs-feed' ), 'sfs_full_capability', 'sfs-feed-flickr-settings', 'sfs_admin_flickr_settings_page' );

  add_action( 'load-' . $flickr, 'sfs_load_page_admin' );

  $yt = add_submenu_page( 'sfs-feed', __( 'Youtube Settings', 'sfs-feed' ), __( 'Youtube', 'sfs-feed' ), 'sfs_full_capability', 'sfs-feed-yt-settings', 'sfs_admin_yt_settings_page' );

  add_action( 'load-' . $yt, 'sfs_load_page_admin' );

//  $insta = add_submenu_page( 'sfs-feed', __( 'Instagram Settings', 'sfs-feed' ), __( 'Instagram', 'sfs-feed' ), 'sfs_full_capability', 'sfs-feed-insta-settings', 'sfs_admin_insta_settings_page' );
//
//  add_action( 'load-' . $insta, 'sfs_load_page_admin' );

}

add_filter( 'set-screen-option', 'sfs_set_screen_options', 10, 3 );

function sfs_set_screen_options( $result, $option, $value ) {
  $sfs_screens = array(
    'sfs_display_per_page' );

  if ( in_array( $option, $sfs_screens ) ) {
    $result = $value;
  }

  return $result;
}


function sfs_load_page_admin() {
  global $plugin_page;
}

add_action( 'admin_enqueue_scripts', 'sfs_admin_enqueue_scripts' );

function sfs_admin_enqueue_scripts( $hook_suffix ){
  if (false === strpos($hook_suffix, 'sfs-feed')) {
    return;
  }
}
/*
 * Add Admin Page views
 */
function sfs_admin_global_settings_page() {
  include_once( SFS_PLUGIN_DIR . '/admin/views/settings.php');
}

function sfs_admin_twitter_settings_page() {
  include_once( SFS_PLUGIN_DIR . '/admin/views/twitter.php');
}

function sfs_admin_fb_settings_page() {
  include_once( SFS_PLUGIN_DIR . '/admin/views/facebook.php');
}

function sfs_admin_insta_settings_page() {
  include_once( SFS_PLUGIN_DIR . '/admin/views/instagram.php');

}

function sfs_admin_yt_settings_page() {
  include_once( SFS_PLUGIN_DIR . '/admin/views/youtube.php');
}

function sfs_admin_flickr_settings_page() {
  include_once( SFS_PLUGIN_DIR . '/admin/views/flickr.php');
}

/* Misc */

add_action( 'sfs_admin_notices', 'sfs_admin_updated_message' );

function sfs_admin_updated_message() {
  if ( empty( $_REQUEST['message'] ) ) {
    return;
  }
}

add_filter( 'plugin_action_links', 'sfs_plugin_action_links', 10, 2 );

function sfs_plugin_action_links( $links, $file ) {
  if ( $file != SFS_PLUGIN_BASENAME ) {
    return $links;
  }

  $settings_link = '<a href="' . menu_page_url( 'sfs-feed', false ) . '">'
    . esc_html( __( 'Settings', 'sfs-feed' ) ) . '</a>';

  array_unshift( $links, $settings_link );

  return $links;
}

add_action( 'sfs_admin_warnings', 'sfs_old_wp_version_error' );

function sfs_old_wp_version_error() {
  $wp_version = get_bloginfo( 'version' );

  if ( ! version_compare( $wp_version, SFS_REQUIRED_WP_VERSION, '<' ) ) {
    return;
  }

  ?>
  <div class="notice notice-warning">
    <p><?php
      /* translators: 1: version of Contact Form 7, 2: version of WordPress, 3: URL */
      echo sprintf( __( '<strong>Social Feed by SPRITES %1$s requires WordPress %2$s or higher.</strong> Please <a href="%3$s">update WordPress</a> first.', 'sfs-feed' ), SFS_PLUGIN_VERSION, SFS_REQUIRED_WP_VERSION, admin_url( 'update-core.php' ) );
      ?></p>
  </div>
  <?php
}

add_action( 'sfs_admin_warnings', 'sfs_not_allowed_to_edit' );

function sfs_not_allowed_to_edit() {
	/* do something */
}

add_action( 'sfs_admin_warnings', 'sfs_notice_bulk_validate_config', 5 );

function sfs_notice_bulk_validate_config() {
  /* do something */
}
