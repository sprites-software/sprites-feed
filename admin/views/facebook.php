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
      // output security fields for the registered setting "wporg"
      settings_fields( 'sfs-option-group' );
      // output setting sections and their fields
      // (sections are registered for "wporg", each field is registered to a specific section)
      do_settings_sections( 'sfs-feed-fb-settings' );
      // output save settings button
      submit_button( 'Save Settings' );
      ?>
	</form>
</div>