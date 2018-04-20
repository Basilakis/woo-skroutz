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
		'meta_key'       => 'skroutz_add',
		'meta_value'     => '1',
		'post_type'      => 'product',
	) );
	wp_reset_postdata();

	header( 'Content-type: text/xml' );
	echo '<?xml version="1.0" encoding="UTF-8"?>
	<webstore>
	<created_at>' . date( 'Y-m-d H:i' ) . '</created_at>
	<products>'; // WPCS: XSS ok.

	foreach ( $products as $product ) {

		// Skip if no image.
		if ( ! has_post_thumbnail( $product ) ) {
			continue;
		}

		if ( ! wc_get_product( $product->ID )->get_stock_quantity() ) {
			continue;
		}
		echo '
		<product>';

			echo '
			<uid>' . absint( $product->ID ) . '</uid>';
			echo '
			<name>' . the_title_attribute( array( 'echo' => false, 'post' => $product ) ) . '</name>';
			echo '
			<priceVat>' . number_format( wc_get_product( $product->ID )->get_price(), 2 ) . '</priceVat>';
			echo '
			<link>' . esc_url_raw( site_url( '?post_type=product&amp;p=' . $product->ID ) ) . '</link>';
			echo '
			<image>' . esc_url_raw( get_the_post_thumbnail_url( $product, 'full' ) ) . '</image>';

			$cat_tree   = array();
			// Level 1
			$term       = get_field( 'skroutz_category', $product->ID );
			$cat_tree[] = $term->term_id;
			// Level 2
			if ( $term->parent ) {
				$term       = get_term( $term->parent, 'skroutz_category' );
				$cat_tree[] = $term->term_id;
			}
			// Level 3
			if ( $term->parent ) {
				$term       = get_term( $term->parent, 'skroutz_category' );
				$cat_tree[] = $term->term_id;
			}
			// Level 4
			if ( $term->parent ) {
				$term       = get_term( $term->parent, 'skroutz_category' );
				$cat_tree[] = $term->term_id;
			}
			// Level 5
			if ( $term->parent ) {
				$term       = get_term( $term->parent, 'skroutz_category' );
				$cat_tree[] = $term->term_id;
			}
			foreach ( $cat_tree as $key => $value ) {
				if ( ! $value ) {
					unset( $cat_tree[ $key ] );
				}
				$cat_tree[ $key ] = get_term( $value, 'skroutz_category' )->name;
			}
			krsort( $cat_tree );
			echo '
			<category>' . wp_strip_all_tags( implode( ' > ', $cat_tree ) ) . '</category>';

			$manufacturer = get_field( 'skroutz_manufacturer' , $product->ID );
			$manufacturer = ( $manufacturer ) ? $manufacturer->name : '';
			echo '
			<manufacturer>' . $manufacturer . '</manufacturer>';
			echo '
			<mpn>' . get_field( 'skroutz_mpn', $product->ID ) . '</mpn>';
			echo '
			<instock>Y</instock>';
			echo '
			<availability>' . get_field( 'skroutz_availability', $product->ID ) . '</availability>';
			echo '
			<shipping>' . number_format( get_field( 'skroutz_shipping_cost', $product->ID ), 2 ) . '</shipping>';
			echo '
			<color>' . wp_strip_all_tags( get_field( 'skroutz_color', $product->ID ) ) . '</color>';
			echo '
			<size>' . wp_strip_all_tags( get_field( 'skroutz_size', $product->ID ) ) . '</size>';

			echo '
		</product>';
	}
	echo '
	</products>
</webstore>';
	exit();
} );

add_filter( 'manage_product_posts_columns', function( $columns ) {
	unset( $columns['author'] );
	$columns['skroutz'] = 'Skroutz';
	return $columns;
} );

add_action( 'manage_product_posts_custom_column', function( $column, $post_id ) {

	if ( 'skroutz' !== $column ) {
		return;
	}
	$val = get_post_meta( $post_id, 'skroutz_add', true );
	if ( ! $val ) {
		return;
	}
	echo '<span class="dashicons dashicons-yes"></span>';
}, 10, 2 );
