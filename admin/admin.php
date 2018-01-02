<?php

//require_once SFS_PLUGIN_DIR . '/admin/includes/admin-functions.php';
//require_once SFS_PLUGIN_DIR . '/admin/includes/SFSHelpTabs.php';
//require_once SFS_PLUGIN_DIR . '/admin/includes/tag-generator.php';
//require_once SFS_PLUGIN_DIR . '/admin/includes/SFSWelcomePanel.php';

add_action( 'admin_init', 'sfs_admin_init' );

function sfs_admin_init() {
  do_action( 'sfs_admin_init' );
}

add_action( 'admin_menu', 'sfs_admin_menu', 9 );

function sfs_admin_menu() {
  global $_wp_last_object_menu;

  $_wp_last_object_menu++;

  add_menu_page( __( 'Social Feed', 'sfs-feed' ),
    __( 'Social', 'sfs-feed' ),
    'sfs_read_feed', 'sfs-feed',
    'sfs_admin_management_page', 'dashicons-world',
    $_wp_last_object_menu );

//  $edit = add_submenu_page( 'sfs-feed',
//    __( 'Edit Contact Form', 'sfs-feed' ),
//    __( 'Contact Forms', 'sfs-feed' ),
//    'sfs_read_feed', 'sfs-feed',
//    'sfs_admin_management_page' );
//
//  add_action( 'load-' . $edit, 'sfs_load_contact_form_admin' );

  $global = add_submenu_page( 'sfs-feed',
    __( 'Global Settings', 'sfs-feed' ),
    __( 'Settings', 'sfs-feed' ),
    'sfs_edit_settings', 'sfs-feed-settings',
    'sfs_admin_settings' );

  add_action( 'load-' . $global, 'sfs_add_submenu_settings' );

}

add_filter( 'set-screen-option', 'sfs_set_screen_options', 10, 3 );

function sfs_set_screen_options( $result, $option, $value ) {
  $sfs_screens = array(
    'sfs_display_per_page' );

  if ( in_array( $option, $sfs_screens ) ) {
    $result = $value;
  }

  return $result;
}

add_action( 'admin_enqueue_scripts', 'sfs_admin_enqueue_scripts' );

function sfs_admin_enqueue_scripts( $hook_suffix ) {
  if ( false === strpos( $hook_suffix, 'sfs-feed' ) ) {
    return;
  }
//
//  wp_enqueue_style( 'contact-form-7-admin',
//    wpcf7_plugin_url( 'admin/css/styles.css' ),
//    array(), WPCF7_VERSION, 'all' );
//
//  if ( wpcf7_is_rtl() ) {
//    wp_enqueue_style( 'contact-form-7-admin-rtl',
//      wpcf7_plugin_url( 'admin/css/styles-rtl.css' ),
//      array(), WPCF7_VERSION, 'all' );
//  }
//
//  wp_enqueue_script( 'wpcf7-admin',
//    wpcf7_plugin_url( 'admin/js/scripts.js' ),
//    array( 'jquery', 'jquery-ui-tabs' ),
//    WPCF7_VERSION, true );
//
//  $args = array(
//    'apiSettings' => array(
//      'root' => esc_url_raw( rest_url( 'contact-form-7/v1' ) ),
//      'namespace' => 'contact-form-7/v1',
//      'nonce' => ( wp_installing() && ! is_multisite() )
//        ? '' : wp_create_nonce( 'wp_rest' ),
//    ),
//    'pluginUrl' => wpcf7_plugin_url(),
//    'saveAlert' => __(
//      "The changes you made will be lost if you navigate away from this page.",
//      'contact-form-7' ),
//    'activeTab' => isset( $_GET['active-tab'] )
//      ? (int) $_GET['active-tab'] : 0,
//    'configValidator' => array(
//      'errors' => array(),
//      'howToCorrect' => __( "How to correct this?", 'contact-form-7' ),
//      'oneError' => __( '1 configuration error detected', 'contact-form-7' ),
//      'manyErrors' => __( '%d configuration errors detected', 'contact-form-7' ),
//      'oneErrorInTab' => __( '1 configuration error detected in this tab panel', 'contact-form-7' ),
//      'manyErrorsInTab' => __( '%d configuration errors detected in this tab panel', 'contact-form-7' ),
//      'docUrl' => WPCF7_ConfigValidator::get_doc_link(),
//    ),
//  );
//
//  if ( ( $post = wpcf7_get_current_contact_form() )
//    && current_user_can( 'wpcf7_edit_contact_form', $post->id() )
//    && wpcf7_validate_configuration() ) {
//    $config_validator = new WPCF7_ConfigValidator( $post );
//    $config_validator->restore();
//    $args['configValidator']['errors'] =
//      $config_validator->collect_error_messages();
//  }
//
//  wp_localize_script( 'wpcf7-admin', 'wpcf7', $args );
//
//  add_thickbox();
//
//  wp_enqueue_script( 'wpcf7-admin-taggenerator',
//    wpcf7_plugin_url( 'admin/js/tag-generator.js' ),
//    array( 'jquery', 'thickbox', 'wpcf7-admin' ), WPCF7_VERSION, true );
}

//function sf_admin_management_page() {
//  if ( $post = sfs_get_current_contact_form() ) {
//    $post_id = $post->initial() ? -1 : $post->id();
//
//    require_once SFS_PLUGIN_DIR . '/admin/includes/editor.php';
//    require_once SFS_PLUGIN_DIR . '/admin/edit-contact-form.php';
//    return;
//  }
//
//  if ( 'validate' == sfs_current_action()
//    && sfs_validate_configuration()
//    && current_user_can( 'sfs_edit_contact_forms' ) ) {
//    sfs_admin_bulk_validate_page();
//    return;
//  }
//
//  $list_table = new WPCF7_Contact_Form_List_Table();
//  $list_table->prepare_items();
//
//  ?>
<!--  <div class="wrap">-->
<!---->
<!--    <h1 class="wp-heading-inline">--><?php
//      echo esc_html( __( 'Contact Forms', 'contact-form-7' ) );
//      ?><!--</h1>-->
<!---->
<!--    --><?php
//    if ( current_user_can( 'wpcf7_edit_contact_forms' ) ) {
//      echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>',
//        esc_url( menu_page_url( 'wpcf7-new', false ) ),
//        esc_html( __( 'Add New', 'contact-form-7' ) ) );
//    }
//
//    if ( ! empty( $_REQUEST['s'] ) ) {
//      echo sprintf( '<span class="subtitle">'
//        /* translators: %s: search keywords */
//        . __( 'Search results for &#8220;%s&#8221;', 'contact-form-7' )
//        . '</span>', esc_html( $_REQUEST['s'] ) );
//    }
//    ?>
<!---->
<!--    <hr class="wp-header-end">-->
<!---->
<!--    --><?php //do_action( 'wpcf7_admin_warnings' ); ?>
<!--    --><?php //wpcf7_welcome_panel(); ?>
<!--    --><?php //do_action( 'wpcf7_admin_notices' ); ?>
<!---->
<!--    <form method="get" action="">-->
<!--      <input type="hidden" name="page" value="--><?php //echo esc_attr( $_REQUEST['page'] ); ?><!--" />-->
<!--      --><?php //$list_table->search_box( __( 'Search Contact Forms', 'contact-form-7' ), 'wpcf7-contact' ); ?>
<!--      --><?php //$list_table->display(); ?>
<!--    </form>-->
<!---->
<!--  </div>-->
<!--  --><?php
//}
//
//function wpcf7_admin_bulk_validate_page() {
//  $contact_forms = WPCF7_ContactForm::find();
//  $count = WPCF7_ContactForm::count();
//
//  $submit_text = sprintf(
//  /* translators: %s: number of contact forms */
//    _n(
//      "Validate %s Contact Form Now",
//      "Validate %s Contact Forms Now",
//      $count, 'contact-form-7' ),
//    number_format_i18n( $count ) );
//
//  ?>
<!--  <div class="wrap">-->
<!---->
<!--    <h1>--><?php //echo esc_html( __( 'Validate Configuration', 'contact-form-7' ) ); ?><!--</h1>-->
<!---->
<!--    <form method="post" action="">-->
<!--      <input type="hidden" name="action" value="validate" />-->
<!--      --><?php //wp_nonce_field( 'wpcf7-bulk-validate' ); ?>
<!--      <p><input type="submit" class="button" value="--><?php //echo esc_attr( $submit_text ); ?><!--" /></p>-->
<!--    </form>-->
<!---->
<!--    --><?php //echo wpcf7_link( __( 'https://contactform7.com/configuration-validator-faq/', 'contact-form-7' ), __( 'FAQ about Configuration Validator', 'contact-form-7' ) ); ?>
<!---->
<!--  </div>-->
<!--  --><?php
//}

//function wpcf7_admin_add_new_page() {
//  $post = wpcf7_get_current_contact_form();
//
//  if ( ! $post ) {
//    $post = WPCF7_ContactForm::get_template();
//  }
//
//  $post_id = -1;
//
//  require_once WPCF7_PLUGIN_DIR . '/admin/includes/editor.php';
//  require_once WPCF7_PLUGIN_DIR . '/admin/edit-contact-form.php';
//}

//function wpcf7_load_integration_page() {
//  $integration = WPCF7_Integration::get_instance();
//
//  if ( isset( $_REQUEST['service'] )
//    && $integration->service_exists( $_REQUEST['service'] ) ) {
//    $service = $integration->get_service( $_REQUEST['service'] );
//    $service->load( wpcf7_current_action() );
//  }
//
//  $help_tabs = new WPCF7_Help_Tabs( get_current_screen() );
//  $help_tabs->set_help_tabs( 'integration' );
//}

//function wpcf7_admin_integration_page() {
//  $integration = WPCF7_Integration::get_instance();
//
//  ?>
<!--  <div class="wrap">-->
<!---->
<!--    <h1>--><?php //echo esc_html( __( 'Integration with Other Services', 'contact-form-7' ) ); ?><!--</h1>-->
<!---->
<!--    --><?php //do_action( 'wpcf7_admin_warnings' ); ?>
<!--    --><?php //do_action( 'wpcf7_admin_notices' ); ?>
<!---->
<!--    --><?php
//    if ( isset( $_REQUEST['service'] )
//      && $service = $integration->get_service( $_REQUEST['service'] ) ) {
//      $message = isset( $_REQUEST['message'] ) ? $_REQUEST['message'] : '';
//      $service->admin_notice( $message );
//      $integration->list_services( array( 'include' => $_REQUEST['service'] ) );
//    } else {
//      $integration->list_services();
//    }
//    ?>
<!---->
<!--  </div>-->
<!--  --><?php
//}

/* Misc */

//add_action( 'wpcf7_admin_notices', 'wpcf7_admin_updated_message' );

//function wpcf7_admin_updated_message() {
//  if ( empty( $_REQUEST['message'] ) ) {
//    return;
//  }
//
//  if ( 'created' == $_REQUEST['message'] ) {
//    $updated_message = __( "Contact form created.", 'contact-form-7' );
//  } elseif ( 'saved' == $_REQUEST['message'] ) {
//    $updated_message = __( "Contact form saved.", 'contact-form-7' );
//  } elseif ( 'deleted' == $_REQUEST['message'] ) {
//    $updated_message = __( "Contact form deleted.", 'contact-form-7' );
//  }
//
//  if ( ! empty( $updated_message ) ) {
//    echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
//    return;
//  }
//
//  if ( 'failed' == $_REQUEST['message'] ) {
//    $updated_message = __( "There was an error saving the contact form.",
//      'contact-form-7' );
//
//    echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
//    return;
//  }
//
//  if ( 'validated' == $_REQUEST['message'] ) {
//    $bulk_validate = WPCF7::get_option( 'bulk_validate', array() );
//    $count_invalid = isset( $bulk_validate['count_invalid'] )
//      ? absint( $bulk_validate['count_invalid'] ) : 0;
//
//    if ( $count_invalid ) {
//      $updated_message = sprintf(
//      /* translators: %s: number of contact forms */
//        _n(
//          "Configuration validation completed. An invalid contact form was found.",
//          "Configuration validation completed. %s invalid contact forms were found.",
//          $count_invalid, 'contact-form-7' ),
//        number_format_i18n( $count_invalid ) );
//
//      echo sprintf( '<div id="message" class="notice notice-warning is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
//    } else {
//      $updated_message = __( "Configuration validation completed. No invalid contact form was found.", 'contact-form-7' );
//
//      echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );
//    }
//
//    return;
//  }
//}
//
//add_filter( 'plugin_action_links', 'wpcf7_plugin_action_links', 10, 2 );
//
//function wpcf7_plugin_action_links( $links, $file ) {
//  if ( $file != WPCF7_PLUGIN_BASENAME ) {
//    return $links;
//  }
//
//  $settings_link = '<a href="' . menu_page_url( 'wpcf7', false ) . '">'
//    . esc_html( __( 'Settings', 'contact-form-7' ) ) . '</a>';
//
//  array_unshift( $links, $settings_link );
//
//  return $links;
//}
//
//add_action( 'wpcf7_admin_warnings', 'wpcf7_old_wp_version_error' );
//
//function wpcf7_old_wp_version_error() {
//  $wp_version = get_bloginfo( 'version' );
//
//  if ( ! version_compare( $wp_version, WPCF7_REQUIRED_WP_VERSION, '<' ) ) {
//    return;
//  }
//
//  ?>
<!--  <div class="notice notice-warning">-->
<!--    <p>--><?php
//      /* translators: 1: version of Contact Form 7, 2: version of WordPress, 3: URL */
//      echo sprintf( __( '<strong>Contact Form 7 %1$s requires WordPress %2$s or higher.</strong> Please <a href="%3$s">update WordPress</a> first.', 'contact-form-7' ), WPCF7_VERSION, WPCF7_REQUIRED_WP_VERSION, admin_url( 'update-core.php' ) );
//      ?><!--</p>-->
<!--  </div>-->
<!--  --><?php
//}
//
//add_action( 'wpcf7_admin_warnings', 'wpcf7_not_allowed_to_edit' );
//
//function wpcf7_not_allowed_to_edit() {
//  if ( ! $contact_form = wpcf7_get_current_contact_form() ) {
//    return;
//  }
//
//  $post_id = $contact_form->id();
//
//  if ( current_user_can( 'wpcf7_edit_contact_form', $post_id ) ) {
//    return;
//  }
//
//  $message = __( "You are not allowed to edit this contact form.",
//    'contact-form-7' );
//
//  echo sprintf(
//    '<div class="notice notice-warning"><p>%s</p></div>',
//    esc_html( $message ) );
//}
//
//add_action( 'wpcf7_admin_warnings', 'wpcf7_notice_bulk_validate_config', 5 );
//
//function wpcf7_notice_bulk_validate_config() {
//  if ( ! wpcf7_validate_configuration()
//    || ! current_user_can( 'wpcf7_edit_contact_forms' ) ) {
//    return;
//  }
//
//  if ( isset( $_GET['page'] ) && 'wpcf7' == $_GET['page']
//    && isset( $_GET['action'] ) && 'validate' == $_GET['action'] ) {
//    return;
//  }
//
//  $result = WPCF7::get_option( 'bulk_validate' );
//  $last_important_update = '4.9';
//
//  if ( ! empty( $result['version'] )
//    && version_compare( $last_important_update, $result['version'], '<=' ) ) {
//    return;
//  }
//
//  $link = add_query_arg(
//    array( 'action' => 'validate' ),
//    menu_page_url( 'wpcf7', false ) );
//
//  $link = sprintf( '<a href="%s">%s</a>', $link, esc_html( __( 'Validate Contact Form 7 Configuration', 'contact-form-7' ) ) );
//
//  $message = __( "Misconfiguration leads to mail delivery failure or other troubles. Validate your contact forms now.", 'contact-form-7' );
//
//  echo sprintf( '<div class="notice notice-warning"><p>%s &raquo; %s</p></div>',
//    esc_html( $message ), $link );
//}
