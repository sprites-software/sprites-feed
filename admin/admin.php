<?php

//require_once SFS_PLUGIN_DIR . '/admin/includes/admin-functions.php';
//require_once SFS_PLUGIN_DIR . '/admin/includes/SFSHelpTabs.php';
//require_once SFS_PLUGIN_DIR . '/admin/includes/tag-generator.php';
require_once SFS_PLUGIN_DIR . '/admin/includes/SFSActivationPanel.php';

add_action( 'admin_init', 'sfs_admin_init' );

function sfs_admin_init() {
	do_action( 'sfs_admin_init' );
}

add_action('admin_init', 'sfs_admin_fb_options');

function sfs_admin_fb_options() {
  // register a new setting for "sfs" page
  register_setting( 'sfs-option-group', 'sfs-fb-credentials' );
  // register a new section in the "sfs-feed-twitter-settings" page
  add_settings_section('sfs-section-fb-app', __( 'Facebook App settings', 'sfs-feed' ), 'sfs_render_settings_section', 'sfs-feed-fb-settings');
  add_settings_field('sfs-fb-app-id', __( 'App ID', 'sfs-feed' ), 'sfs_render_fb_settings_field', 'sfs-feed-fb-settings', 'sfs-section-fb-app', ['label_for' => 'sfs-fb-app-id',]);
  add_settings_field('sfs-fb-app-secret', __( 'App Secret', 'sfs-feed' ), 'sfs_render_fb_settings_field', 'sfs-feed-fb-settings', 'sfs-section-fb-app', ['label_for' => 'sfs-fb-app-secret',]);
}

add_action('admin_init', 'sfs_admin_yt_options');

function sfs_admin_yt_options() {
  register_setting( 'sfs-option-group', 'sfs-yt-credentials' );
  add_settings_section('sfs-section-yt-app', __( 'Youtube API settings', 'sfs-feed' ), 'sfs_render_settings_section', 'sfs-feed-yt-settings');
  add_settings_field('sfs-yt-api-key', __( 'Api Key', 'sfs-feed' ), 'sfs_render_yt_settings_field', 'sfs-feed-yt-settings', 'sfs-section-yt-app', ['label_for' => 'sfs-yt-api-key',]);
}

add_action('admin_init', 'sfs_admin_flickr_options');

function sfs_admin_flickr_options() {
  register_setting( 'sfs-option-group', 'sfs-flickr-credentials' );
  add_settings_section('sfs-section-flickr-app', __( 'Flickr App settings', 'sfs-feed' ), 'sfs_render_settings_section', 'sfs-feed-flickr-settings');
  add_settings_field('sfs-flickr-api-key', __( 'API Key', 'sfs-feed' ), 'sfs_render_flickr_settings_field', 'sfs-feed-flickr-settings', 'sfs-section-flickr-app', ['label_for' => 'sfs-flickr-api-key',]);
  add_settings_field('sfs-flickr-api-secret', __( 'API Secret', 'sfs-feed' ), 'sfs_render_flickr_settings_field', 'sfs-feed-flickr-settings', 'sfs-section-flickr-app', ['label_for' => 'sfs-flickr-api-secret',]);
}

add_action('admin_init', 'sfs_admin_twitter_options');

function sfs_admin_twitter_options() {
  register_setting( 'sfs-option-group', 'sfs-twitter-credentials' );
  add_settings_section('sfs-section-api-key', __( 'Twitter API key settings', 'sfs-feed' ), 'sfs_render_settings_section', 'sfs-feed-twitter-settings');
  add_settings_field('sfs-api-oa-token', __( 'API OAuth Token', 'sfs-feed' ),'sfs_render_settings_field','sfs-feed-twitter-settings', 'sfs-section-api-key', ['label_for' => 'sfs-api-oa-token',]);
  add_settings_field('sfs-api-oa-token-secret', __( 'API OAuth Token Secret', 'sfs-feed' ), 'sfs_render_settings_field', 'sfs-feed-twitter-settings', 'sfs-section-api-key', ['label_for' => 'sfs-api-oa-token-secret',]);
  add_settings_field('sfs-api-oa-consumer-key', __( 'API Consumer Key', 'sfs-feed' ), 'sfs_render_settings_field', 'sfs-feed-twitter-settings', 'sfs-section-api-key', ['label_for' => 'sfs-api-oa-consumer-key',]);
  add_settings_field('sfs-api-oa-consumer-key-secret', __( 'API Consumer Key Secret', 'sfs-feed' ), 'sfs_render_settings_field', 'sfs-feed-twitter-settings', 'sfs-section-api-key', ['label_for' => 'sfs-api-oa-consumer-key-secret',]);
}

function sfs_render_settings_section( $args ) {
  ?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Configure your application access keys and configure API calls', 'sfs-feed' ); ?></p>
  <?php
}

function sfs_render_settings_field($args)
{
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
