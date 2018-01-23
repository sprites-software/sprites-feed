<?php

require_once SFS_PLUGIN_DIR . '/entity/FacebookApiClass.php';
require_once SFS_PLUGIN_DIR . '/entity/TwitterApiClass.php';
require_once SFS_PLUGIN_DIR . '/entity/YoutubeApiClass.php';
require_once SFS_PLUGIN_DIR . '/entity/FlickrApiClass.php';
require_once SFS_PLUGIN_DIR . '/includes/functions.php';
require_once SFS_PLUGIN_DIR . '/includes/capabilities.php';
require_once SFS_PLUGIN_DIR . '/includes/l10n.php';

if ( is_admin() ) {
  require_once SFS_PLUGIN_DIR . '/admin/admin.php';
} else {
  require_once SFS_PLUGIN_DIR . '/includes/controller.php';
}

class SFS {

  public static function load_modules() {
    self::load_module( 'import' );
  }

  protected static function load_module( $mod ) {
    $dir = SFS_PLUGIN_MODULES_DIR;

    if ( empty( $dir ) || ! is_dir( $dir ) ) {
      return false;
    }

    $file = path_join( $dir, $mod . '.php' );

    if ( file_exists( $file ) ) {
      include_once $file;
    }
  }

  public static function get_option( $name, $default = false ) {
    $option = get_option( 'sfs-feed' );

    if ( false === $option ) {
      return $default;
    }

    if ( isset( $option[$name] ) ) {
      return $option[$name];
    } else {
      return $default;
    }
  }

  public static function update_option( $name, $value ) {
    $option = get_option( 'sfs-feed' );
    $option = ( false === $option ) ? array() : (array) $option;
    $option = array_merge( $option, array( $name => $value ) );
    update_option( 'sfs-feed', $option );
  }
}

add_action( 'plugins_loaded', 'sfs' );

function sfs() {
  sfs_load_textdomain();
  sfs_start_cron_jobs();
  SFS::load_modules();
}

add_action( 'init', 'sfs_init' );

function sfs_init() {
  sfs_get_request_uri();
  sfs_register_post_types();

//  var_dump(sfs_cron_persist_albums());
  do_action( 'sfs_init' );
}

add_action( 'admin_init', 'sfs_upgrade' );

function sfs_upgrade() {
  $old_ver = SFS::get_option( 'version', '0' );
  $new_ver = SFS_PLUGIN_VERSION;

  if ( $old_ver == $new_ver ) {
    return;
  }

  do_action( 'sfs_upgrade', $new_ver, $old_ver );

  SFS::update_option( 'version', $new_ver );
}

/* Install and default settings */

add_action( 'activate_' . SFS_PLUGIN_BASENAME, 'sfs_install' );

function sfs_install() {
  if ( $opt = get_option( 'sfs-feed' ) ) {
    return;
  }

  sfs_load_textdomain();
  sfs_register_post_types();
  sfs_upgrade();

  if ( get_posts( array( 'post_type' => 'sfs_feed' ) ) ) {
    return;
  }
}

/* Enqueue scripts and styles */

add_action('admin_enqueue_scripts', 'sfs_enqueue_bundles');

function sfs_enqueue_bundles( $hook ){
  // if ( $hook != 'toplevel_page_'.SFS_PLUGIN_NAME) {
  //   return;
  // }
  wp_enqueue_style('sfs_admin_stylesheet', plugins_url('/dist/admin.styles.css', __FILE__));
  wp_enqueue_script('sfs_admin_scripts', plugins_url('/dist/admin.bundle.js', __FILE__), array(), 1, false);
}
// http://localhost:8888/wordpress-api/Users/krystof/Sites/wordpress-api/wp-content/plugins/sfs-feed/dist/admin.styles.css?ver=4.8.5
