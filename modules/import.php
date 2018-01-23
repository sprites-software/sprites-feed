<?php
/**
 * Module for cron/persist action trigger, import now button
 */

add_action('wp_ajax_sfs_run_import_facebook', 'sfs_run_import_facebook');
add_action('wp_ajax_nopriv_sfs_run_import_facebook', 'sfs_run_import_facebook');
add_action('wp_ajax_sfs_run_import_flickr', 'sfs_run_import_flickr');
add_action('wp_ajax_nopriv_sfs_run_import_flickr', 'sfs_run_import_flickr');
add_action('wp_ajax_sfs_run_import_twitter', 'sfs_run_import_twitter');
add_action('wp_ajax_nopriv_sfs_run_import_twitter', 'sfs_run_import_twitter');
add_action('wp_ajax_sfs_run_import_youtube', 'sfs_run_import_youtube');
add_action('wp_ajax_nopriv_sfs_run_import_youtube', 'sfs_run_import_youtube');

function sfs_run_import_facebook() {
  try {
    do_action('sfs_cron_hook');
  } catch (\Exception $e) {
    echo json_encode('There was an Exception thrown in the run_import_facebook function, the error states: %s', $e->getMessage());
  }
  wp_die();
  die();
}

function sfs_run_import_flickr() {
  try {
    do_action('sfs_cron_hook_albums');
  } catch (\Exception $e) {
    echo json_encode('There was an Exception thrown in the run_import_facebook function, the error states: %s', $e->getMessage());
  }
  wp_die();
  die();
}

function sfs_run_import_twitter() {
  try {
    do_action('sfs_cron_hook_secondary');
  } catch (\Exception $e) {
    echo json_encode('There was an Exception thrown in the run_import_facebook function, the error states: %s', $e->getMessage());
  }
  wp_die();
  die();
}
function sfs_run_import_youtube() {
  try {
    do_action('sfs_cron_hook_videos');
  } catch (\Exception $e) {
    echo json_encode('There was an Exception thrown in the run_import_facebook function, the error states: %s', $e->getMessage());
  }
  wp_die();
  die();
}

add_action('admin_footer', 'run_script');

function run_script() {
  ?>
  <script>
    (function(){
      var httpRequest;

      function makeRequest(action) {
        httpRequest = new XMLHttpRequest();

        if (!httpRequest) {
          console.log('Giving up, can not create a XMLHttp Request Instance.');
          return false;
        }
        httpRequest.onreadystatechange = alertContents;
        httpRequest.open('POST', '<?php _e(admin_url('admin-ajax.php')); ?>', true);
        httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httpRequest.send('action=' + action);
      }

      function alertContents() {
        try {
            if (httpRequest.readyState === XMLHttpRequest.DONE) {
                if (httpRequest.status === 200) {
                    console.log(httpRequest.responseText);
                } else {
                    console.log('There was a problem with the request.');
                }
            }
        } catch(e) {
            alert('Caught Exception: ' + e.description);
        }
      }

      var el = document.getElementById('import-posts-btn');
      var action = (el) ? el.dataset.case : null;
      if(el && action) {
        el.addEventListener('click', makeRequest(action));
      }
    })();
  </script>
  <?php
}