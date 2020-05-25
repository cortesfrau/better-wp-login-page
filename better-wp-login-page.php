<?php

/**
 * Plugin Name: Better WP Login Page
 * Text Domain: better_wp_login_page
 * Description: This plugin allows users to enhance the default login page.
 * Plugin URI: https://github.com/cortesfrau/better-wp-login-page/
 * Version: 1.0.1
 * Author: Lluís Cortès
 * Author URI: https://lluiscortes.com
 * License: GPLv2 or later
 * Domain Path: /languages
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Constants
define( 'BWPLP_VERSION', '1.1.0' );
define( 'BWPLP_BASE', __FILE__ );
define( 'BWPLP_PATH', __DIR__ );
define( 'BWPLP_URL', plugins_url( '', BWPLP_BASE ) );
define( 'BWPLP_BASENAME', plugin_basename( __FILE__ ) );


// Plugin Main Class
class Better_WP_Login_Page {

  // Construct
  public function __construct() {

    // Admin menu
    add_action('admin_menu', [$this, 'settings_page'] );

    // Register settings
    add_action( 'admin_init', [$this, 'register_settings'] );

    // Admin Scripts & Styles
    add_action( 'admin_enqueue_scripts', [$this, 'admin_scripts_styles'] );

    // Login Page Styles
    add_action( 'login_enqueue_scripts', [$this, 'public_scripts_styles'] );

    // Actions links in plugins admin screen
    add_filter( 'plugin_action_links_' . BWPLP_BASENAME, [$this, 'add_action_links'] );

    // Change Logo Link
    add_filter( 'login_headerurl', [$this, 'change_logo_link'] );

    // Change Logo Title
    add_filter( 'login_headertext', [$this, 'change_logo_title'] );

  }


  // Admin Scripts & Styles
  function admin_scripts_styles( $hook ) {

    // Do not load if we are not in the plugin settings page
    if ( !strstr( $hook, 'better-wp-login-page' ) ) {
      return;
    }

    // WordPress Media Library
    wp_enqueue_media();

    // Color Picker
    wp_enqueue_style( 'wp-color-picker' ); // <-- Color picker
    wp_enqueue_script( 'wp-color-picker-alpha', BWPLP_URL . '/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), '2.1.4', true );

    wp_enqueue_style( 'bwplp-admin-css', BWPLP_URL . '/css/bwplp-admin.css', array(), BWPLP_VERSION );
    wp_enqueue_script( 'bwplp-admin-js', BWPLP_URL . '/js/bwplp-admin.js', array( 'jquery', 'wp-color-picker' ), BWPLP_VERSION );

  }


  // Admin menu
  public function settings_page() {
    add_submenu_page(
      'options-general.php',
      'Better WP Login Page',
      'Better WP Login Page',
      'manage_options',
      'better-wp-login-page',
      [$this, 'settings_content']
    );
  }


  // Actions links in plugins admin screen
  public function add_action_links( $links ) {
    $links[] = '<a href="' . admin_url( 'options-general.php?page=better-wp-login-page' ) . '">' . __( 'Settings', 'better_wp_login_page' ) . '</a>';
    return $links;
  }


  //-----------------//
  //--- FUNCTIONS ---//
  //-----------------//

  // Change the logo link
  function change_logo_link() {
    return home_url();
  }

  // Change the logo title
  function change_logo_title() {
    return get_bloginfo('name');
  }

  // Image Uploader
  function image_uploader( $option ) {

    $logo_id = get_option( $option );
    $default_image = plugins_url('img/no-image.png', __FILE__);

    if ( ! empty( $logo_id ) ) {

      $src = wp_get_attachment_image_src( $logo_id, 'full' )[0];
      $value = $logo_id;

    } else {

      $src = $default_image;
      $value = '';

    }

    $text = __( 'Subir', 'better_wp_login_page' );

    // Print HTML field
    $html =

      '<div class="bwplp-img-upload-form">
        <img data-src="' . $default_image . '" src="' . $src . '" width="150" height="auto" />
        <div>
          <input type="hidden" name="' . $option . '" value="' . $value . '" />
          <button type="submit" class="bwplp-upload-image-btn button">' . $text . '</button>
          <button type="submit" class="bwplp-remove-image-btn button">&times;</button>
        </div>
      </div>';

    echo $html;

  }

  // Change the logo Image
  function public_scripts_styles() {

    // JS Script
    wp_enqueue_script( 'bwplp-public-js', BWPLP_URL . '/js/bwplp-public.js', array( 'jquery' ), BWPLP_VERSION, true );

    // CSS Stylesheet
    wp_enqueue_style( 'bwplp-public-css', BWPLP_URL . '/css/bwplp-public.css', array(), BWPLP_VERSION );

    $logo_id        = $this->get_settings()['logo_img'];
    $accent_color   = $this->get_settings()['accent_color'];
    $submit_btn_bg  = $this->get_settings()['submit_btn_bg'];
    $bg_color       = $this->get_settings()['bg_color'];
    $form_bg        = $this->get_settings()['form_bg'];
    $bg_img         = $this->get_settings()['bg_img'];
    $label_color    = $this->get_settings()['label_color'];

    ?>

    <style type="text/css">

      /*------------------------*/
      /*--- Background Image ---*/
      /*------------------------*/
      <?php if ( ! empty( $bg_img ) ) {

        $bg_img = wp_get_attachment_image_src( $bg_img, 'full' )[0];

        ?>

        .login {
          background-image: url(<?php echo $bg_img; ?>);
        }

      <?php } else { ?>

        .login {
          background: <?php echo $bg_color; ?>;
        }

     <?php } ?>

      .login #backtoblog a:hover, .login #nav a:hover {
        color: <?php echo $accent_color; ?> !important;
      }

      /*------------*/
      /*--- LOGO ---*/
      /*------------*/
      <?php if ( ! empty( $logo_id ) ) {

        $logo_url = wp_get_attachment_image_src( $logo_id, 'full' )[0];

        ?>

        #login h1 a {
          background-image: url(<?php echo $logo_url; ?>) !important;
          background-size: contain !important;
          background-repeat: no-repeat !important;
          background-position: center center !important;
          width: 100% !important;
          min-height: 120px !important;
        }

      <?php } ?>

      /*-------------------*/
      /*--- Login Block ---*/
      /*-------------------*/
      #login {
        background: <?php echo $form_bg; ?> !important;
      }

      /*------------------*/
      /*--- Login Form ---*/
      /*------------------*/
      #loginform input:focus {
        border-color: <?php echo $accent_color; ?>;
        box-shadow: 0 0 5px 0 <?php echo $accent_color; ?>;
      }
      #loginform input[type=submit] {
        background: <?php echo $submit_btn_bg; ?>;
        border-color: <?php echo $submit_btn_bg; ?>;
      }
      #loginform .dashicons {
        color: <?php echo $accent_color; ?> !important;
      }
      #loginform label {
        color: <?php echo $label_color; ?> !important;
      }

      /*----------------*/
      /*--- Messages ---*/
      /*----------------*/
      .login .message,
      .login .success {
        border-color: <?php echo $accent_color; ?> !important;
      }


    </style>

  <?php }



  //----------------//
  //--- SETTINGS ---//
  //----------------//

  // Register settings
  public function register_settings() {
    register_setting( 'better_wp_login_page_settings', 'bwplp_logo_img' );
    register_setting( 'better_wp_login_page_settings', 'bwplp_submit_btn_bg' );
    register_setting( 'better_wp_login_page_settings', 'bwplp_accent_color' );
    register_setting( 'better_wp_login_page_settings', 'bwplp_bg_color' );
    register_setting( 'better_wp_login_page_settings', 'bwplp_form_bg' );
    register_setting( 'better_wp_login_page_settings', 'bwplp_bg_img' );
    register_setting( 'better_wp_login_page_settings', 'bwplp_label_color' );
  }

  // Get Settings
  function get_settings(){

    $settings = [
      'logo_img'        => get_option( 'bwplp_logo_img' ),
      'bg_color'        => get_option( 'bwplp_bg_color' ),
      'submit_btn_bg'   => get_option( 'bwplp_submit_btn_bg' ),
      'accent_color'    => get_option( 'bwplp_accent_color' ),
      'form_bg'         => get_option( 'bwplp_form_bg' ),
      'bg_img'          => get_option( 'bwplp_bg_img' ),
      'label_color'     => get_option( 'bwplp_label_color' ),
    ];

    return $settings;
  }

  // Settings page content
  public function settings_content() {

    // Settings Data
    $settings = $this->get_settings();

    ?>

    <div class="wrap">
      <h1>Better WP Login Page</h1>

      <form method="post" action="options.php">

        <?php settings_fields( 'better_wp_login_page_settings' ); ?>
        <?php do_settings_sections( 'better_wp_login_page_settings' ); ?>

        <table class="form-table">
          <tr>
            <th><?php echo __( 'Custom Logo', 'better_wp_login_page' ); ?></th>
            <td><?php $this->image_uploader( 'bwplp_logo_img' ); ?></td>
          </tr>
          <tr>
            <th><?php echo __( 'Background image', 'better_wp_login_page' ); ?></th>
            <td><?php $this->image_uploader( 'bwplp_bg_img' ); ?></td>
          </tr>
        </table>

        <!-- Colors : START -->
        <h2><?php echo __( 'Colors', 'better_wp_login_page'); ?></h2>
        <table class="form-table">
          <tr>
            <th><?php echo __( 'Main background', 'better_wp_login_page' ); ?></th>
            <td><input type="text" name="bwplp_bg_color" value="<?php echo $settings['bg_color']; ?>" class="color-picker" data-alpha="true" /></td>
          </tr>
          <tr>
            <th><?php echo __( 'Form background', 'better_wp_login_page' ); ?></th>
            <td><input type="text" name="bwplp_form_bg" value="<?php echo $settings['form_bg']; ?>" class="color-picker" data-alpha="true" /></td>
          </tr>
          <tr>
            <th><?php echo __( 'Labels', 'better_wp_login_page' ); ?></th>
            <td><input type="text" name="bwplp_label_color" value="<?php echo $settings['label_color']; ?>" class="color-picker" data-alpha="true" /></td>
          </tr>
          <tr>
            <th><?php echo __( 'Accent color', 'better_wp_login_page' ); ?></th>
            <td><input type="text" name="bwplp_accent_color" value="<?php echo $settings['accent_color']; ?>" class="color-picker" data-alpha="true" /></td>
          </tr>
          <tr>
            <th><?php echo __( 'Submit button background)', 'better_wp_login_page' ); ?></th>
            <td><input type="text" name="bwplp_submit_btn_bg" value="<?php echo $settings['submit_btn_bg']; ?>" class="color-picker" data-alpha="true" /></td>
          </tr>
        </table>
        <!-- Colors : END -->


        <?php submit_button(); ?>

      </form>
    </div>

  <?php }



}


// Instantiation
$better_wp_login_page = new Better_WP_Login_Page();