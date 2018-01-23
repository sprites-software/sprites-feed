<?php
if ( ! current_user_can( 'manage_options' ) ) {
  return;
}
if ( isset( $_GET['settings-updated'] ) ) {
  add_settings_error( 'sfs_messages', 'sfs_message', __( 'Settings Saved', 'sprites-feed' ), 'updated' );
}
?>
<div class="wrap">
	<?php settings_errors( 'sfs_messages' ); ?>
	<?php do_action( 'sfs_admin_warnings' ); ?>
	<?php do_action( 'sfs_admin_notices' ); ?>
	<div class="Global">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<div class="Global-wrap">
			<form action="options.php" method="post">
		      <?php
		      settings_fields( 'sfs-option-group' );
		      do_settings_sections( 'sfs-feed-fb-settings' );
		      submit_button( 'Save Settings' );
		      ?>
			</form>
			<button id="import-facebook-posts-btn" class="button button-secondary" type="button">Import now</button>
		</div>
	</div>
</div>
<!--<script>-->
<!--    'use strict';-->
<!--    class ImportNow {-->
<!--        constructor(data) {-->
<!--            this.httpRequest = {};-->
<!--            this.data = data;-->
<!--        }-->
<!--        makeRequest(url, data) {-->
<!--            this.httpRequest = new XMLHttpRequest();-->
<!---->
<!--            if (!this.httpRequest) {-->
<!--                console.log('Giving up, can not create a XMLHttp Request Instance.');-->
<!--                return false;-->
<!--            }-->
<!--            this.httpRequest.onreadystatechange = this.alertContents();-->
<!--            this.httpRequest.open('POST', url);-->
<!--            this.httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');-->
<!--            this.httpRequest.send('data=' + encodeURIComponent(data));-->
<!--        }-->
<!--        alertContents() {-->
<!--            if (this.httpRequest.readyState === XMLHttpRequest.DONE) {-->
<!--                if (this.httpRequest.status === 200) {-->
<!--                    console.log(this.httpRequest.responseText);-->
<!--                } else {-->
<!--                    console.log('There was a problem with the request.');-->
<!--                    console.log(this.httpRequest);-->
<!--                }-->
<!--            }-->
<!--        }-->
<!--    }-->
<!--    var app = new ImportNow({'action': 'sfs_run_import_facebook'});-->
<!--    document.getElementById('import-facebook-posts-btn').addEventListener('click', app.makeRequest('<?php //_e(admin_url('admin-ajax.php')); ?>', app.data)); -->
</script>
