<?php

function sfs_activation_panel() {
  $classes = 'welcome-panel';

  $vers = (array) get_user_meta( get_current_user_id(),
    'sfs_hide_welcome_panel_on', true );

  if ( sfs_version_grep( sfs_version( 'only_major=1' ), $vers ) ) {
    $classes .= ' hidden';
  }

  ?>
  <div id="welcome-panel" class="<?php echo esc_attr( $classes ); ?>" style="padding:0 1rem .5rem;">
    <?php wp_nonce_field( 'sfs-welcome-panel-nonce', 'welcomepanelnonce', false ); ?>
    <a class="welcome-panel-close" href="<?php echo esc_url( menu_page_url( 'sfs-feed', false ) ); ?>"><?php echo esc_html( __( 'Dismiss', 'sfs-feed' ) ); ?></a>

    <div class="welcome-panel-content">
      <div class="welcome-panel-column-container">

        <div class="welcome-panel-column" style="width: 75%;">
          <h3 style="line-height: 1.5rem;"><span class="dashicons dashicons-warning"></span> <?php echo esc_html( __( "Don't have your activation key yet?", 'sfs-feed' ) ); ?></h3>
          <p><?php echo esc_html( __( "Upgrade your license to access all features of the Social Feed by SPRITES plugin and&nbsp;create a&nbsp;mixed feed ready to display and filter between your social feeds. Integrate all the social networks and&nbsp;update your&nbsp;site automatically with&nbsp;every post on your facebook&nbsp;timeline or your social&nbsp;profile.", 'sfs-feed' ) ); ?></p>
        </div>

      </div>
    </div>
  </div>
  <?php
}

add_action( 'wp_ajax_sfs-update-welcome-panel', 'sfs_admin_ajax_welcome_panel' );

function sfs_admin_ajax_welcome_panel() {
  check_ajax_referer( 'sfs-welcome-panel-nonce', 'welcomepanelnonce' );

  $vers = get_user_meta( get_current_user_id(),
    'sfs_hide_welcome_panel_on', true );

  if ( empty( $vers ) || ! is_array( $vers ) ) {
    $vers = array();
  }

  if ( empty( $_POST['visible'] ) ) {
    $vers[] = sfs_version( 'only_major=1' );
  }

  $vers = array_unique( $vers );

  update_user_meta( get_current_user_id(), 'sfs_hide_welcome_panel_on', $vers );

  wp_die( 1 );
}
