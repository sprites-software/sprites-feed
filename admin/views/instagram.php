
<?php
if ( ! current_user_can( 'manage_options' ) ) {
  return;
}
if ( isset( $_GET['settings-updated'] ) ) {
  add_settings_error( 'sfs_messages', 'sfs_message', __( 'Settings Saved', 'sprites-feed' ), 'updated' );
}
?>
<style>
	.Global {
		padding: 2rem;
		background: #fff;
		border: 1px solid #efefef;
	}
</style>
<div class="wrap">
  <?php settings_errors( 'sfs_messages' ); ?>
  <?php do_action( 'sfs_admin_warnings' ); ?>
  <?php do_action( 'sfs_admin_notices' ); ?>
	<div class="Global">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<div class="Global-wrap">
			<form action="options.php" method="post">
              <?php
	              settings_fields( 'sfs-insta-option-group' );
	              do_settings_sections( 'sfs-feed-insta-settings' );
	              submit_button( 'Save Settings' );
              ?>
			</form>
			<button id="import-posts-btn" class="button button-secondary" type="button" data-case="sfs_run_import_instagram">Import now</button>
		</div>
	</div>
</div>