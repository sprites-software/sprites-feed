<?php

//require_once SFS_PLUGIN_DIR . '/admin/includes/admin-functions.php';
//require_once SFS_PLUGIN_DIR . '/admin/includes/SFSHelpTabs.php';
//require_once SFS_PLUGIN_DIR . '/admin/includes/tag-generator.php';
//require_once SFS_PLUGIN_DIR . '/admin/includes/SFSWelcomePanel.php';

add_action( 'admin_init', 'sfs_admin_init' );

function sfs_admin_init() {
  do_action( 'sfs_admin_init' );
}

add_action( 'admin_menu', 'sfs_admin_menu', 9 );

function sfs_admin_menu() {
  global $_wp_last_object_menu;

  $_wp_last_object_menu++;

  add_menu_page( __( 'Social Feed', 'sfs-feed' ),
    __( 'Settings', 'sfs-feed' ),
    'sfs_full_capability', 'sfs-feed',
    'sfs_admin_management_page', 'dashicons-admin-site',
    $_wp_last_object_menu );

  $edit = add_submenu_page( 'sfs-feed',
    __( 'Twitter Settings', 'sfs-feed' ),
    __( 'Twitter', 'sfs-feed' ),
    'sfs_read_capability', 'sfs-feed-twitter-settings',
    'sfs_admin_twitter_settings_page' );

  add_action( 'load-' . $edit, 'sfs_load_page_admin' );

  $global = add_submenu_page( 'sfs-feed',
    __( 'Facebook Settings', 'sfs-feed' ),
    __( 'Facebook', 'sfs-feed' ),
    'sfs_full_capability', 'sfs-feed-fb-settings',
    'sfs_admin_fb_settings_page' );

  add_action( 'load-' . $global, 'sfs_load_page_admin' );

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

function sfs_admin_management_page() {
	?>
  <div class="wrap">

    <h1 class="wp-heading-inline">
      <?php echo esc_html( __( 'Social Feed by Sprites', 'sfs-feed' ) ); ?>
    </h1>

    <hr class="wp-header-end">

    <?php //do_action( 'sfs_admin_warnings' ); ?>
	<?php //sfs_welcome_panel(); ?>
    <?php //do_action( 'sfs_admin_notices' ); ?>

  </div>
  <?php
}

function sfs_admin_twitter_settings_page() {
  ?>
	<div class="wrap">

		<h1><?php echo esc_html( __( 'Twitter API Settings', 'sfs-feed' ) ); ?></h1>

      <?php do_action( 'sfs_admin_warnings' ); ?>
      <?php do_action( 'sfs_admin_notices' ); ?>

	</div>
  <?php
}

function sfs_admin_fb_settings_page() {
  ?>
  <div class="wrap">

    <h1><?php echo esc_html( __( 'Facebook API Settings', 'sfs-feed' ) ); ?></h1>

    <?php do_action( 'sfs_admin_warnings' ); ?>
    <?php do_action( 'sfs_admin_notices' ); ?>

  </div>
  <?php
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
