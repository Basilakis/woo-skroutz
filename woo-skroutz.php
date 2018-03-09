<?php
/**
 * A plugin to handle skroutz.gr XML generation on a per-product basis.
 *
 * @since 1.0
 * @package woo-skroutz
 *
 * Plugin Name: Woo Skroutz
 * Plugin URI: https://wpmu.io
 * Description: Add Skroutz.gr XML generation per-product.
 * Author: Aristeides Stathopoulos
 * Author URI: https://aristath.github.io
 * Developer: WPMU.IO
 * Developer URI: https://wpmu.io
 * Requires at least: 4.9
 * Tested up to: 4.9
 * Version: 1.0
 * Text Domain: skroutz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once dirname( __FILE__ ) . '/inc/class-woo-skroutz.php';
new Woo_Skroutz();
