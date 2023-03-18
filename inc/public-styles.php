<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

$settings = $this->get_settings();

$logo_id                  = $settings['logo_img'];
$accent_color             = $settings['accent_color'];
$submit_btn_bg            = $settings['submit_btn_bg'];
$submit_btn_text          = $settings['submit_btn_text_color'];
$bg_color                 = $settings['bg_color'];
$form_bg                  = $settings['form_bg'];
$bg_img                   = $settings['bg_img'];
$label_color              = $settings['label_color'];
$footer_link_color        = $settings['footer_link_color'];
$remove_language_switcher = $settings['remove_language_switcher'];
$form_box_shadow          = $settings['form_box_shadow'];
$form_box_border_radius   = $settings['form_box_border_radius'];

?>

<style type="text/css">

  :root {
    --transition-base:    .2s all ease-in-out;
    --less-opacity:       .75;
    --box-shadow-sm:       0 .25rem 1rem rgba(0,0,0, .15);
    --box-shadow-lg:       0 .5rem 2rem rgba(0, 0, 0, .2);
    --box-shadow-xl:       0 .75rem 3rem rgba(0, 0, 0, .3);
    --accent-color:       <?php echo $accent_color; ?>;
    --bg-color:           <?php echo $bg_color; ?>;
    --submit-btn-bg:      <?php echo $submit_btn_bg; ?>;
    --submit-btn-text:    <?php echo $submit_btn_text; ?>;
    --label-color:        <?php echo $label_color; ?>;
    --form-bg:            <?php echo $form_bg; ?>;
    --footer-link-color:  <?php echo $footer_link_color; ?>
  }

  /*------------------------*/
  /*--- Background Image ---*/
  /*------------------------*/
  <?php if (!empty($bg_img)) { $bg_img = wp_get_attachment_image_src($bg_img, 'full')[0]; ?>

    .login {
      background-image: url(<?php echo $bg_img; ?>) !important;
    }

  <?php } else { ?>

    .login {
      background: var(--bg-color) !important;
    }

  <?php } ?>

  .login {
    background-size: cover !important;
  }

  /*------------*/
  /*--- LOGO ---*/
  /*------------*/
  <?php if (!empty($logo_id)) { $logo_url = wp_get_attachment_image_src($logo_id, 'full')[0]; ?>

    #login h1 a {
      background-image: url(<?php echo $logo_url; ?>) !important;
      background-size: contain !important;
      background-repeat: no-repeat !important;
      background-position: center center !important;
      width: 100% !important;
      min-height: 120px !important;
      transition: var(--transition-base) !important;
    }
    #login h1 a:hover {
      opacity: var(--less-opacity) !important;
    }

  <?php } ?>

  /*-------------------*/
  /*--- Login Block ---*/
  /*-------------------*/
  #login {
    background: var(--form-bg) !important;
    position: relative !important;
    margin: 0 5% !important;
    width: auto !important;
    padding: 2rem !important;
    border-radius: <?php echo $form_box_border_radius.'px !important;'; ?>

    <?php switch ($form_box_shadow) {
      case 'sm':
        echo 'box-shadow: var(--box-shadow-sm) !important';
        break;
      case 'lg':
        echo 'box-shadow: var(--box-shadow-lg) !important';
        break;
      case 'xl':
        echo 'box-shadow: var(--box-shadow-xl) !important';
        break;
      default:
        echo 'box-shadow: none !important';
    } ?>

  }

  /*------------------*/
  /*--- Login Form ---*/
  /*------------------*/
  #loginform {
    background: transparent !important;
    box-shadow: none !important;
    border: 0 !important;
    padding: 0 !important;
  }

  /*--------------------------*/
  /*--- Lost Password Form ---*/
  /*--------------------------*/
  #lostpasswordform {
    padding: 0 !important;
    border: 0 !important;
    background: transparent !important;
  }

  /*--------------*/
  /*--- Inputs ---*/
  /*--------------*/
  input {
    border-radius: 0 !important;
    font-size: 14px !important;
    padding: 10px !important;
    line-height: 1 !important;
  }
  input:focus {
    border-color: var(--accent-color) !important;
    box-shadow: 0 0 5px 0 var(--accent-color) !important;
  }
  input[type=submit] {
    background: var(--submit-btn-bg) !important;
    border-color: var(--submit-btn-bg) !important;
    color: var(--submit-btn-text) !important;
    display: block !important;
    width: 100% !important;
    margin: 1rem 0 0 0 !important;
    padding: 14px !important;
    transition: var(--transition-base) !important;
  }
  input[type=submit]:hover {
    opacity: var(--less-opacity) !important;
  }
  input[type=checkbox]:checked::before {
    content: '✓' !important;
    font-weight: 900 !important;
    color: var(--accent-color) !important;
    line-height: 20px !important;
    font-size: 14px !important;
  }
  #loginform #rememberme {
    padding: 0 !important;
  }

  /*--------------*/
  /*--- Labels ---*/
  /*--------------*/
  label {
    color: var(--label-color) !important;
    font-size: 13px !important;
    margin: 0 0 6px 0 !important;
  }

  /*-------------------------*/
  /*--- Footer Link Color ---*/
  /*-------------------------*/
  .login #backtoblog a,
  .login #nav a {
    color: var(--footer-link-color) !important;
  }

  /*----------------*/
  /*--- Messages ---*/
  /*----------------*/
  .login .message,
  .login .success {
    border-color: var(--accent-color) !important;
  }

  /*-------------*/
  /*--- Icons ---*/
  /*-------------*/
  #loginform .dashicons {
    color: var(--accent-color) !important;
  }

  /*--------------------*/
  /*--- Bottom Links ---*/
  /*--------------------*/
  .login #backtoblog,
  .login #nav {
    padding: 0 !important;
  }
  .login #backtoblog a:hover,
  .login #nav a:hover {
    transition: var(--transition-base) !important;
    color: var(--accent-color) !important;
  }
  .login #nav {
    text-align: center !important;
    margin: 1rem 0 0 0 !important;
  }
  .login #backtoblog {
    display: none !important;
  }

  /*-------------------------*/
  /*--- Language Switcher ---*/
  /*-------------------------*/
  <?php if ($remove_language_switcher) { ?>

    #language-switcher {
      display: none !important;
      visibility: hidden !important;
    }

  <?php } ?>

  #language-switcher {
    margin-top: 2rem !important;
  }
  #language-switcher select {
    margin: 0 0 0 10px !important;
    width: calc(100% - 34px) !important;
    min-height: 40px !important;
  }
  #language-switcher label {
    margin: 0 !important;
  }


  /*---------------------*/
  /*--- Media Queries ---*/
  /*---------------------*/
  @media only screen and (min-width: 768px) {

    /* Login Block */
    #login {
      width: 320px !important;
      margin: 0 auto !important;
    }
  }

</style>
