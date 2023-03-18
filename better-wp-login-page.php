<?php

/**
 * Plugin Name: Better WP Login Page
 * Text Domain: better_wp_login_page
 * Description: This plugin allows users to enhance the default login page.
 * Plugin URI: https://github.com/cortesfrau/better-wp-login-page/
 * Version: 1.1.2
 * Author: Lluís Cortès
 * Author URI: https://lluiscortes.com
 * License: GPLv2 or later
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Constants
define('BWPLP_VERSION', '1.1.2');
define('BWPLP_BASE', __FILE__);
define('BWPLP_PATH', __DIR__);
define('BWPLP_URL', plugins_url('', BWPLP_BASE));
define('BWPLP_BASENAME', plugin_basename(__FILE__));

// Plugin Main Class
class Better_WP_Login_Page {

  // Construct
  public function __construct() {

    // Admin menu
    add_action('admin_menu', [$this, 'settings_page']);

    // Register settings
    add_action('admin_init', [$this, 'register_settings']);

    // Admin Scripts & Styles
    add_action('admin_enqueue_scripts', [$this, 'admin_scripts_styles']);

    // Public Scripts & Styles
    add_action('login_enqueue_scripts', [$this, 'public_scripts_styles']);

    // Actions links in plugins admin screen
    add_filter('plugin_action_links_'.BWPLP_BASENAME, [$this, 'add_action_links']);

    // Change Logo Link
    add_filter('login_headerurl', [$this, 'change_logo_link']);

    // Change Logo Title
    add_filter('login_headertext', [$this, 'change_logo_title']);

  }


  // Admin menu
  public function settings_page() {
    add_submenu_page(
      'options-general.php',
      'Login Page',
      'Login Page',
      'manage_options',
      'better-wp-login-page',
      [$this, 'settings_content']
    );
  }


  // Actions links in plugins admin screen
  public function add_action_links($links) {
    $links[] = '<a href="'.admin_url('options-general.php?page=better-wp-login-page').'">'.__('Settings', 'better_wp_login_page').'</a>';
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
    $default_image = plugins_url('dist/img/no-image.png', __FILE__);

    if (!empty($logo_id)) {
      $src = wp_get_attachment_image_src( $logo_id, 'full' )[0];
      $value = $logo_id;
    } else {
      $src = $default_image;
      $value = '';
    }

    $text = __('Subir', 'better_wp_login_page');

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


  //-------------------------------//
  //--- Public Scripts & Styles ---//
  //-------------------------------//
  function public_scripts_styles() {
    wp_enqueue_style('bwplp-login-styles', BWPLP_URL.'/dist/css/bwplp-bundle.css', null, BWPLP_VERSION);
    wp_enqueue_script('bwplp-login-scripts',  BWPLP_URL.'/dist/js/bwplp-bundle.js', array('jquery'), BWPLP_VERSION, true);
    include_once ('inc/public-styles.php');
  }


  //------------------------------//
  //--- Admin Scripts & Styles ---//
  //------------------------------//
  function admin_scripts_styles($hook) {

    // Do not load if we are not in the plugin settings page
    if (!strstr($hook, 'better-wp-login-page')) return;

    // WordPress Media Library
    wp_enqueue_media();

    // Color Picker
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script( 'wp-color-picker');

    // Admin styles & scripts
    wp_enqueue_style('bwplp-admin-styles', BWPLP_URL.'/dist/css/bwplp-admin.css', null, BWPLP_VERSION);
    wp_enqueue_script('bwplp-admin-scripts', BWPLP_URL.'/dist/js/bwplp-admin.js', array('jquery', 'wp-color-picker'), BWPLP_VERSION, true);
  }


  //----------------//
  //--- SETTINGS ---//
  //----------------//

  // Register settings
  public function register_settings() {
    register_setting('better_wp_login_page_settings', 'bwplp_logo_img');
    register_setting('better_wp_login_page_settings', 'bwplp_submit_btn_bg');
    register_setting('better_wp_login_page_settings', 'bwplp_submit_btn_text_color');
    register_setting('better_wp_login_page_settings', 'bwplp_accent_color');
    register_setting('better_wp_login_page_settings', 'bwplp_bg_color');
    register_setting('better_wp_login_page_settings', 'bwplp_form_bg');
    register_setting('better_wp_login_page_settings', 'bwplp_bg_img');
    register_setting('better_wp_login_page_settings', 'bwplp_label_color');
    register_setting('better_wp_login_page_settings', 'bwplp_footer_link_color');
    register_setting('better_wp_login_page_settings', 'bwplp_remove_language_switcher');
    register_setting('better_wp_login_page_settings', 'bwplp_form_box_shadow');
    register_setting('better_wp_login_page_settings', 'bwplp_form_box_border_radius');
  }

  // Get Settings
  private function get_settings(){

    $settings = [
      'logo_img'                  => get_option('bwplp_logo_img'),
      'bg_color'                  => get_option('bwplp_bg_color') ?: '#f4f4f4',
      'submit_btn_bg'             => get_option('bwplp_submit_btn_bg') ?: '#2b60de',
      'submit_btn_text_color'     => get_option('bwplp_submit_btn_text_color') ?: '#fff',
      'accent_color'              => get_option('bwplp_accent_color') ?: '#2b60de',
      'form_bg'                   => get_option('bwplp_form_bg') ?: '#fff',
      'bg_img'                    => get_option('bwplp_bg_img'),
      'label_color'               => get_option('bwplp_label_color') ?: '#222',
      'footer_link_color'         => get_option('bwplp_footer_link_color') ?: '#2b60de',
      'remove_language_switcher'  => get_option('bwplp_remove_language_switcher'),
      'form_box_shadow'           => get_option('bwplp_form_box_shadow'),
      'form_box_border_radius'    => get_option('bwplp_form_box_border_radius') ?: 0,
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
            <th><?php echo __( 'Custom logo', 'better_wp_login_page' ); ?></th>
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
            <td><input type="text" name="bwplp_bg_color" value="<?php echo $settings['bg_color']; ?>" class="color-picker" data-alpha-enabled="true" /></td>
          </tr>
          <tr>
            <th><?php echo __( 'Form background', 'better_wp_login_page' ); ?></th>
            <td><input type="text" name="bwplp_form_bg" value="<?php echo $settings['form_bg']; ?>" class="color-picker" data-alpha-enabled="true" /></td>
          </tr>
          <tr>
            <th><?php echo __( 'Accent color', 'better_wp_login_page' ); ?></th>
            <td><input type="text" name="bwplp_accent_color" value="<?php echo $settings['accent_color']; ?>" class="color-picker" data-alpha-enabled="true" /></td>
          </tr>
          <tr>
            <th><?php echo __( 'Labels', 'better_wp_login_page' ); ?></th>
            <td><input type="text" name="bwplp_label_color" value="<?php echo $settings['label_color']; ?>" class="color-picker" data-alpha-enabled="true" /></td>
          </tr>
          <tr>
            <th><?php echo __( 'Footer link color', 'better_wp_login_page' ); ?></th>
            <td><input type="text" name="bwplp_footer_link_color" value="<?php echo $settings['footer_link_color']; ?>" class="color-picker" data-alpha-enabled="true" /></td>
          </tr>
        </table>

        <h3><?php echo __( 'Submit button', 'better_wp_login_page' ); ?></h3>
        <table class="form-table">
          <tr>
            <th><?php echo __( 'Text', 'better_wp_login_page' ); ?></th>
            <td><input type="text" name="bwplp_submit_btn_text_color" value="<?php echo $settings['submit_btn_text_color']; ?>" class="color-picker" data-alpha-enabled="true" /></td>
          </tr>
          <tr>
            <th><?php echo __( 'Background', 'better_wp_login_page' ); ?></th>
            <td><input type="text" name="bwplp_submit_btn_bg" value="<?php echo $settings['submit_btn_bg']; ?>" class="color-picker" data-alpha-enabled="true" /></td>
          </tr>
        </table>
        <!-- Colors : END -->

        <!-- Styles : START -->
        <h2><?php echo __( 'Styles', 'better_wp_login_page'); ?></h2>
        <table class="form-table">
          <tr>
            <th><?php echo __( 'Form box shadow', 'better_wp_login_page' ); ?></th>
            <td>
              <select name="bwplp_form_box_shadow">
                <option value="none" <?php echo $settings['form_box_shadow'] == 'none' ? 'selected' : ''; ?>><?php _e('No shadow', 'better_wp_login_page'); ?></option>
                <option value="sm" <?php echo $settings['form_box_shadow'] == 'sm' ? 'selected' : ''; ?>><?php _e('Small shadow', 'better_wp_login_page'); ?></option>
                <option value="lg" <?php echo $settings['form_box_shadow'] == 'lg' ? 'selected' : ''; ?>><?php _e('Regular shadow', 'better_wp_login_page'); ?></option>
                <option value="xl" <?php echo $settings['form_box_shadow'] == 'xl' ? 'selected' : ''; ?>><?php _e('Larger shadow', 'better_wp_login_page'); ?></option>
              </select>
            </td>
          </tr>
          <tr>
            <th><?php echo __( 'Form box border radius', 'better_wp_login_page' ); ?></th>
            <td><input type="number" name="bwplp_form_box_border_radius" value="<?php echo $settings['form_box_border_radius']; ?>" /></td>
          </tr>
        </table>
        <!-- Styles : END -->

        <!-- Misc : START -->
        <h2><?php echo __( 'Misc', 'better_wp_login_page'); ?></h2>
        <table class="form-table">
          <tr>
            <th><?php echo __( 'Remove the language switcher', 'better_wp_login_page' ); ?></th>
            <td><input type="checkbox" name="bwplp_remove_language_switcher" value="1" <?php echo $settings['remove_language_switcher'] ? 'checked' : ''; ?> /></td>
          </tr>
        </table>



        <!-- Misc : END -->

        <?php submit_button(); ?>

      </form>
    </div>

  <?php }

}

// Instantiation
$better_wp_login_page = new Better_WP_Login_Page();
