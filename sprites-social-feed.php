<?php
/**
  Plugin Name:  Social Feed by SPRITES
  Plugin URI:   https://sprites.co/sprites-social-feed
  Description:  Easy to use wordpress plugin that helps to connect your site with your social networks into a single filterable mixed feed, configure your credentials to get started.
  Version:      0.1.1
  Author:       SPRITES SOFTWARE, s.r.o.
  Author URI:   https://sprites.co/
  License:      GPL2
  License URI:  https://www.gnu.org/licenses/gpl-2.0.html
  Text Domain:  sfs-feed
*/

define( 'SFS_PLUGIN_VERSION', '0.1.1' );

define( 'SFS_REQUIRED_WP_VERSION', '4.7' );

define( 'SFS_PLUGIN_FILE', __FILE__ );

define( 'SFS_PLUGIN_BASENAME', plugin_basename( SFS_PLUGIN_FILE ) );

define( 'SFS_PLUGIN_NAME', trim( dirname( SFS_PLUGIN_BASENAME ), '/' ) );

define( 'SFS_PLUGIN_DIR', untrailingslashit( dirname( SFS_PLUGIN_FILE ) ) );

define( 'SFS_PLUGIN_MODULES_DIR', SFS_PLUGIN_DIR . '/modules' );


if ( ! defined( 'SFS_ADMIN_READ_CAPABILITY' ) ) {
  define( 'SFS_ADMIN_READ_CAPABILITY', 'edit_posts' );
}

if ( ! defined( 'SFS_ADMIN_READ_WRITE_CAPABILITY' ) ) {
  define( 'SFS_ADMIN_READ_WRITE_CAPABILITY', 'publish_pages' );
}

require_once SFS_PLUGIN_DIR . '/vendor/autoload.php';
require_once SFS_PLUGIN_DIR . '/settings.php';
