<?php

namespace SFS\entity;

class SpritesSocialFeed {

  const post_type = 'sfs_social_feed';

  public static function register_post_type() {
    register_post_type( self::post_type, array(
      'labels' => array(
        'name' => __( 'Social Feeds', 'sfs-feed' ),
        'singular_name' => __( 'Social Feed', 'sfs-feed' ),
      ),
      'rewrite' => false,
      'query_var' => false,
      'menu_icon' => 'dashicons-admin-site'
    ) );
  }
}