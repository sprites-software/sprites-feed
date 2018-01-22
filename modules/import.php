<?php
/**
 * Module for cron/persist action trigger, import now button
 */

add_action('wp_ajax_sfs_run_import_facebook', 'sfs_run_import_facebook');
add_action('wp_ajax_nopriv_sfs_run_import_facebook', 'sfs_run_import_facebook');

function sfs_run_import_facebook() {
  try {
    do_action('sfs_cron_hook');
  } catch (\Exception $e) {
    echo json_encode('There was an Exception thrown in the run_import_facebook function, the error states: %s', $e->getMessage());
  }
  wp_die();
  die();
}

add_action('admin_footer', 'run_script');

function run_script() {
//  var_dump(sfs_run_import_facebook());
  ?>
  <script>
    (function(){
      var httpRequest;

      function makeRequest() {
        httpRequest = new XMLHttpRequest();

        if (!httpRequest) {
          console.log('Giving up, can not create a XMLHttp Request Instance.');
          return false;
        }
        httpRequest.onreadystatechange = alertContents;
        httpRequest.open('POST', '<?php _e(admin_url('admin-ajax.php')); ?>', true);
        httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httpRequest.send('action=sfs_run_import_facebook');
      }

      function alertContents() {
        try {
            if (httpRequest.readyState === XMLHttpRequest.DONE) {
                if (httpRequest.status === 200) {
                    console.log(httpRequest.responseText);
                } else {
                    console.log(httpRequest.responseText);
                    console.log('There was a problem with the request.');
                }
            }
        } catch(e) {
            alert('Caught Exception: ' + e.description);
        }
      }

      var el = document.getElementById('import-facebook-posts-btn');
      if(el) {
        el.addEventListener('click', makeRequest);
      }
    })();
  </script>
  <?php
}