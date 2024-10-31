<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionConfigurations_Setup_Install {
	

	public static function install(){
	
		if ( !class_exists( 'WooCommerce' ) ) { 
		  deactivate_plugins('product-configurations-table');
		  wp_die( __('Product Configurations Table requires WooCommerce to run. Please install WooCommerce and activate.', 'product-configurations-table'));
	  }

    if ( version_compare( WC()->version, '3.0', "<" ) ) {
      wp_die(sprintf(__('WooCommerce %s or higher is required (You are running %s)', 'product-configurations-table'), '3.0', WC()->version));
    }	
    	
		self::create_tables();
				
	}


	private static function create_tables(){
		global $wpdb;

		$wpdb->hide_errors();
		 
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta(self::get_schema());
	}


	private static function get_schema(){
		global $wpdb;

		$collate = '';

		if ($wpdb->has_cap( 'collation')){
			if (!empty( $wpdb->charset)){
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if (!empty( $wpdb->collate)){
				$collate .= " COLLATE $wpdb->collate";
			}
		}
		
		return "
CREATE TABLE {$wpdb->base_prefix}optionconfigurations_product (
  `ocf_product_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) unsigned NOT NULL,
  `option_ids` varchar(255) DEFAULT NULL,    
  PRIMARY KEY (ocf_product_id)  
) $collate;	
CREATE TABLE {$wpdb->base_prefix}optionconfigurations_product_options (
  `ocf_product_options_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) unsigned NOT NULL,
  `option_0` varchar(100),
  `option_1` varchar(100),
  `option_2` varchar(100),
  `option_3` varchar(100),
  `option_4` varchar(100),
  `option_5` varchar(100),
  `option_6` varchar(100),
  `option_7` varchar(100),
  `option_8` varchar(100),
  `option_9` varchar(100),    
  PRIMARY KEY (ocf_product_options_id)  
) $collate;		
		";
		
	}

}
