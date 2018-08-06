<?php
/*
  Plugin Name: Form Name AddOn
  Plugin URI: https://www.gravityaddons.com/
  Description: Updates the form tag of Gravity Forms to include the name of the form
  Version: 0.1.1
  Author: Alquemie
  Author URI: https://www.alquemie.net/
*/

if ( ! class_exists( 'GFForms' ) ) {
	die();
}

if (! defined('GRAVITYADD_FORMNAME_VERSION')) {
    define('GRAVITYADD_FORMNAME_VERSION', get_plugin_data(__FILE__)['Version']);
}

if ( ! class_exists( 'AqFormName' ) ) :
    class AqFormName {
    
        public static function load() {
            if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
                return;
            }
            
            require_once( 'class-formname-GFAddOn.php' );
            GFAddOn::register( 'FormNameAddOn' );
        }
    
    }

    add_action( 'gform_loaded', array( 'AqFormName', 'load' ), 3 );
endif;
