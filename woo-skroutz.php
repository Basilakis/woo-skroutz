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

/**
 * Register custom taxonomies.
 */
add_action( 'init', function() {
	register_taxonomy( 'skroutz_category', array( 'product' ), array(
		'hierarchical'      => true,
		'labels'            => array(
			'name'              => 'Κατηγορίες Skroutz',
			'singular_name'     => 'Κατηγορία Skroutz',
			'search_items'      => 'Αναζήτηση',
			'all_items'         => 'Όλες οι κατηγορίες',
			'parent_item'       => 'Γονική Κατηγορία',
			'parent_item_colon' => 'Γονική Κατηγορία:',
			'edit_item'         => 'Επεξεργασία Κατηγορίας',
			'update_item'       => 'Ενημέρωση Κατηγορίας',
			'add_new_item'      => 'Προσθήκη Κατηγορίας',
			'new_item_name'     => 'Όνομα Νέας Κατηγορίας',
			'menu_name'         => 'Κατηγορία Skroutz',
		),
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
	) );
	register_taxonomy( 'skroutz_manufacturer', array( 'product' ), array(
		'hierarchical'      => false,
		'labels'            => array(
			'name'              => 'Κατασκευαστής (Skroutz)',
			'singular_name'     => 'Κατασκευαστής (Skroutz)',
			'search_items'      => 'Αναζήτηση',
			'all_items'         => 'Όλοι οι κατασκευαστές',
			'edit_item'         => 'Επεξεργασία Κατασκευαστή',
			'update_item'       => 'Ενημέρωση Κατασκευαστή',
			'add_new_item'      => 'Προσθήκη Κατασκευαστή',
			'new_item_name'     => 'Όνομα Νέου Κατασκευαστή',
			'menu_name'         => 'Κατασκευαστής (Skroutz)',
		),
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
	) );
}, 5 );

/**
 * Print XML.
 */
add_action( 'init', function() {
	if ( ! isset( $_GET['skroutz-xml'] ) || 'get.xml' !== $_GET['skroutz-xml'] ) {
		return;
	}

	$products = get_posts( array(
		'posts_per_page' => -1,
		'meta_key'       => 'skroutz_add_to_skroutz',
		'meta_value'     => 'add-to-skroutz',
		'post_type'      => 'product',
	) );
	wp_reset_postdata();

	header( 'Content-type: text/xml' );
	?>
	<?xml version="1.0" encoding="UTF-8"?>
	<webstore>
		<created_at><?php echo date( 'Y-m-d H:i' ); // WPCS: XSS ok. ?></created_at>
		<products>
			<?php foreach ( $products as $product ) : ?>
				<product>
				</product>
			<?php endforeach; ?>
		</products>
	</webstore>
	<?php
	exit();
} );
