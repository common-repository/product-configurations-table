<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

global $wpdb;

$wpdb->query("DROP TABLE IF EXISTS {$wpdb->base_prefix}optionconfigurations_product");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->base_prefix}optionconfigurations_product_options");
