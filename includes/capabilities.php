<?php

add_filter( 'map_meta_cap', 'sfs_map_meta_cap', 10, 4 );

function sfs_map_meta_cap( $caps, $cap, $user_id, $args ) {
  $meta_caps = array(
    'sfs_full_capability' => SFS_ADMIN_READ_WRITE_CAPABILITY,
    'sfs_read_capability' => SFS_ADMIN_READ_CAPABILITY,
    'sfs_manage_capability' => 'manage_options',
    'sfs_submit' => 'read',
  );

  $meta_caps = apply_filters( 'sfs_map_meta_cap', $meta_caps );

  $caps = array_diff( $caps, array_keys( $meta_caps ) );

  if ( isset( $meta_caps[$cap] ) ) {
    $caps[] = $meta_caps[$cap];
  }

  return $caps;
}
