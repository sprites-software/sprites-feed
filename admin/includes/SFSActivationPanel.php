<?php

function sfs_activation_panel() {
  $classes = 'welcome-panel';

  ?>
  <div id="welcome-panel" class="<?php echo esc_attr( $classes ); ?>" style="padding:0 1rem .5rem;">
    <a class="welcome-panel-close" href="<?php echo esc_url( menu_page_url( 'sfs-feed', false ) ); ?>"><?php echo esc_html( __( 'Dismiss', 'sfs-feed' ) ); ?></a>

    <div class="welcome-panel-content" style="max-width: 100%">
      <div class="welcome-panel-column-container">

        <div class="welcome-panel-column" style="width: calc(50% - 2rem); box-sizing: border-box; padding: 1rem 2rem;">
          <h3 style="line-height: 1.5rem;"><span class="dashicons dashicons-warning"></span> <?php echo esc_html( __( "Don't have your activation key yet?", 'sfs-feed' ) ); ?></h3>
          <p><?php echo esc_html( __( "Upgrade your license to access all features of the Sprites Social Feed plugin and&nbsp;create a&nbsp;mixed feed ready to display and filter between your social feeds. Integrate all the social networks and&nbsp;update your&nbsp;site automatically with&nbsp;every post on your facebook&nbsp;timeline or your social&nbsp;profile.", 'sfs-feed' ) ); ?></p>
        </div>
		<div class="welcome-panel-column" style="width: calc(50% - 2rem); box-sizing: border-box; padding: 1rem 2rem;">
		  <h3 style="line-height: 1.5rem;"><span class="dashicons dashicons-info"></span> <?php echo esc_html( __( "Not sure how to setup?", 'sfs-feed' ) ); ?></h3>
		  <p><?php echo esc_html( __( "Contact us at hello@sprites.co, we'll be happy to help with your plugin setup and integration to your theme. There's also a bug bounty on security issues with the social plugin. Or&nbsp;visit&nbsp;http://github.com/sprites-feed to see the Docs.", 'sfs-feed' ) ); ?></p>
		</div>

      </div>
    </div>
  </div>
  <?php
}
