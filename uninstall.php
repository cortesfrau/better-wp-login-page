<?php

// If uninstall not called from WordPress, then exit
if (!defined( 'WP_UNINSTALL_PLUGIN')) exit;

// Delete options
foreach (wp_load_alloptions() as $option => $value) {
  if (strpos($option, 'bwplp_') === 0) {
    delete_option($option);
  }
}
