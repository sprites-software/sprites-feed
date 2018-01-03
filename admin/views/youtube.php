<?php
if ( ! current_user_can( 'manage_options' ) ) {
  return;
}
if ( isset( $_GET['settings-updated'] ) ) {
  // add settings saved message with the class of "updated"
  add_settings_error( 'sfs_messages', 'sfs_message', __( 'Settings Saved', 'sfs-feed' ), 'updated' );
}
?>

<div class="wrap">

	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

  <?php settings_errors( 'sfs_messages' ); ?>
  <?php do_action( 'sfs_admin_warnings' ); ?>
  <?php do_action( 'sfs_admin_notices' ); ?>

	<form action="options.php" method="post">
      <?php
      settings_fields( 'sfs-yt-option-group' );
      do_settings_sections( 'sfs-feed-yt-settings' );
      submit_button( 'Save Settings' );
      ?>
	</form>
</div>