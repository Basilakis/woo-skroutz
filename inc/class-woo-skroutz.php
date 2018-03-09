<?php
/**
 * The main plugin class.
 *
 * @since 1.0
 * @package woo-skroutz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Woo_Skroutz' ) ) {
	return;
}

/**
 * The main plugin class.
 *
 * @since 1.0
 */
class Woo_Skroutz {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'init', array( $this, 'print_xml' ), 20 );
		add_action( 'init', array( $this, 'add_skroutz_cats_taxonomy' ), 5 );
	}

	/**
	 * Add a skroutz_categories taxonomy to products.
	 *
	 * @since 1.0
	 * @access public
	 * @return void
	 */
	public function add_skroutz_cats_taxonomy() {
		$args = array(
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
			// 'rewrite'           => array( 'slug' => 'skroutz-category' ),
		);
		register_taxonomy( 'skroutz_category', array( 'product' ), $args );
	}

	/**
	 * Prints the XML.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function print_xml() {
		if ( isset( $_GET['skroutz-xml'] ) && 'get.xml' === $_GET['skroutz-xml'] ) {
			header( 'Content-type: text/xml' );
			echo $this->get_xml(); // WPCS: XSS ok.
			exit();
		}
	}

	/**
	 * Get meta.
	 *
	 * @access public
	 * @since 1.0
	 * @param string $name The meta-name.
	 * @return mixed The post-meta value.
	 */
	public function get_meta( $name ) {
		global $post;

		$field = get_post_meta( $post->ID, $name, true );
		if ( ! empty( $field ) ) {
			return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
		}
		return false;
	}

	/**
	 * Add the metabox.
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function add_meta_box() {
		add_meta_box(
			'skroutz-skroutz',
			__( 'Skroutz', 'skroutz' ),
			array( $this, 'html' ),
			'product',
			'side',
			'high'
		);
	}

	/**
	 * Gets an array of categories we need.
	 *
	 * @access private
	 * @since 1.0
	 * @return array
	 */
	protected function get_categories() {
		return array(
			'453' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Ακουστικές Κιθάρες',
			'457' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Κλασικές Κιθάρες',
			'454' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Ηλεκτρικές Κιθάρες',
			'459' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Ηλεκτρικά Μπάσα',
			'475' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Αρμόνια & Keyboards',
			'456' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Πιάνα',
			'460' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Μπουζούκια',
			'477' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Διάφορα Μουσικά Όργανα',
			'461' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Τουμπελέκια',
			'783' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Κρουστά',
			'455' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Σετ Ντράμς',
			'463' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Βιολιά & Βιολοντσέλα',
			'476' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Κλαρινέτα',
			'464' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Μπαγλαμάδες',
			'480' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Ντραμς',
			'474' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Λύρες',
			'462' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Φλογέρες',
			'468' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Τζουράδες',
			'458' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Ακορντεόν',
			'471' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Τρομπέτες',
			'465' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Μαντολίνα',
			'466' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Σαξόφωνα',
			'473' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Λαούτα',
			'479' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Φλάουτα',
			'478' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Ντέφι',
			'470' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Ταμπουράδες',
			'469' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Ούτι',
			'467' => 'Hobby - Αθλητισμός > Μουσικά Όργανα > Τρομπόνια',
			'553' => 'Hobby - Αθλητισμός > Αξεσουάρ Μουσικών Οργάνων > Μπαγκέτες',
			'554' => 'Hobby - Αθλητισμός > Αξεσουάρ Μουσικών Οργάνων > Θήκες Μουσικών Οργάνων',
			'559' => 'Hobby - Αθλητισμός > Αξεσουάρ Μουσικών Οργάνων > Βάσεις & Αναλόγια',
			'557' => 'Hobby - Αθλητισμός > Αξεσουάρ Μουσικών Οργάνων > Διάφορα Εγχόρδων',
			'550' => 'Hobby - Αθλητισμός > Αξεσουάρ Μουσικών Οργάνων > Χορδές',
			'551' => 'Hobby - Αθλητισμός > Αξεσουάρ Μουσικών Οργάνων > Μαγνήτες',
		);
	}

	/**
	 * The metabox HTML.
	 *
	 * @access public
	 * @since 1.0
	 * @param WP_Post $post The WP_Post object.
	 * @return void
	 */
	public function html( $post ) {
		wp_nonce_field( '_skroutz_nonce', 'skroutz_nonce' );
		$categories     = $this->get_categories();
		$availabilities = array(
			''          => 'Αυτόματο',
			'immediate' => 'Άμεση παραλαβή / Παράδoση 1 έως 3 ημέρες',
			'1to3'      => 'Παράδοση σε 1 - 3 ημέρες',
			'4to10'     => 'Παράδοση σε 4 - 10 ημέρες',
			'orderonly' => 'Κατόπιν Παραγγελίας',
		);
		?>
		<input type="checkbox" name="skroutz_add_to_skroutz" id="skroutz_add_to_skroutz" value="add-to-skroutz" <?php echo ( $this->get_meta( 'skroutz_add_to_skroutz' ) === 'add-to-skroutz' ) ? 'checked' : ''; ?>>
		<label for="skroutz_add_to_skroutz">Προσθήκη σε skroutz</label>
		<hr>

		<h4><label for="skroutz_category">Κατηγορία Skroutz</label></h4>
		<p>Επιλέξτε την κατηγορία στην οποία το προϊόν θα πρέπει να καταχωρηθεί στο skroutz.</p>
		<select name="skroutz_category" id="skroutz_category">
			<?php foreach ( $categories as $slug => $label ) : ?>
				<option <?php echo ( $slug === $this->get_meta( 'skroutz_category' ) ) ? 'selected' : ''; ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<hr>

		<h4><label for="skroutz_shipping_cost">Έξοδα αποστολής</label></h4>
		<p>Αφήστε το κενό για αυτόματο.</p>
		<input type="number" min="0" max="100" step="0.1" name="skroutz_shipping_cost" id="skroutz_shipping_cost" value="<?php echo $this->get_meta( 'skroutz_shipping_cost' ); // WPCS: XSS ok. ?>">
		<hr>

		<h4><label for="skroutz_description">Περιγραφή</label></h4>
		<p>Αφήστε το κενό για αυτόματο.</p>
		<textarea name="skroutz_description" id="skroutz_description" style="width:100%;"><?php echo $this->get_meta( 'skroutz_description' ); // WPCS: XSS ok. ?></textarea>
		<hr>

		<h4><label for="skroutz_availability">Διαθεσιμότητα</label></h4>
		<select name="skroutz_availability" id="skroutz_availability">
			<?php foreach ( $availabilities as $key => $val ) : ?>
				<option <?php echo ( $key === $this->get_meta( 'skroutz_availability' ) ) ? 'selected' : ''; ?>><?php echo $val; // WPCS: XSS ok. ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Save the post-meta.
	 *
	 * @access public
	 * @since 1.0
	 * @param int $post_id The psot-ID.
	 * @return void
	 */
	public function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['skroutz_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['skroutz_nonce'] ) ), '_skroutz_nonce' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['skroutz_add_to_skroutz'] ) ) {
			update_post_meta( $post_id, 'skroutz_add_to_skroutz', sanitize_text_field( wp_unslash( $_POST['skroutz_add_to_skroutz'] ) ) );
		} else {
			update_post_meta( $post_id, 'skroutz_add_to_skroutz', null );
		}

		if ( isset( $_POST['skroutz_category'] ) ) {
			update_post_meta( $post_id, 'skroutz_category', sanitize_text_field( wp_unslash( $_POST['skroutz_category'] ) ) );
		}
		if ( isset( $_POST['skroutz_shipping_cost'] ) ) {
			update_post_meta( $post_id, 'skroutz_shipping_cost', sanitize_text_field( wp_unslash( $_POST['skroutz_shipping_cost'] ) ) );
		}
		if ( isset( $_POST['skroutz_description'] ) ) {
			update_post_meta( $post_id, 'skroutz_description', sanitize_text_field( wp_unslash( $_POST['skroutz_description'] ) ) );
		}
		if ( isset( $_POST['skroutz_availability'] ) ) {
			update_post_meta( $post_id, 'skroutz_availability', sanitize_text_field( wp_unslash( $_POST['skroutz_availability'] ) ) );
		}
	}

	/**
	 * Gets the products.
	 *
	 * @since 1.0
	 * @access protected
	 * @return array
	 */
	protected function get_products() {
		$args  = array(
			'posts_per_page' => -1,
			'meta_key'       => 'skroutz_add_to_skroutz',
			'meta_value'     => 'add-to-skroutz',
			'post_type'      => 'product',
		);
		$posts_results = get_posts( $args );
		wp_reset_postdata();

		$posts = array();
		foreach ( $posts_results as $post_result ) {
			$posts[] = array(
				'post' => $post_result,
				'meta' => get_post_meta( $post_result->ID ),
			);
		}
		return $posts;
	}

	/**
	 * Generate the XML.
	 *
	 * @access public
	 * @since 1.0
	 * @return string
	 */
	public function get_xml() {
		$xml  = '<?xml version="1.0" encoding="UTF-8"?><webstore>';
		$xml .= '<created_at>' . date( 'Y-m-d H:i' ) . '</created_at>';
		$xml .= '<products>';
		foreach ( $this->get_products() as $product ) {
			if ( ! $product['meta']['_thumbnail_id'] || ! $product['meta']['_thumbnail_id'][0] ) {
				continue;
			}
			$id = $product['post']->ID;
			$xml .= '<product>';
			$xml .= '<mpn><![CDATA[' . $id . ']]></mpn>';
			$xml .= '<uid>' . $id . '</uid>';
			$xml .= '<name><![CDATA[' . $product['post']->post_title . ']]></name>';
			$xml .= '<link><![CDATA[' . site_url( '?post_type=product&p=' . $id ) . ']]></link>';
			$xml .= '<image><![CDATA[' . wp_get_attachment_image_src( $product['meta']['_thumbnail_id'][0] )[0] . ']]></image>';
			$xml .= '<category><![CDATA[' . $product['meta']['skroutz_category'][0] . ']]></category>';
			$xml .= '<price>' . number_format( $product['meta']['_price'][0], 2 ) . '</price>';
			$xml .= '<instock>Y</instock>';
			$xml .= '<shipping>' . number_format( $product['meta']['skroutz_shipping_cost'][0], 2 ) . '</shipping>';
			$xml .= '<availability>Άμεση παραλαβή / Παράδoση 1 έως 3 ημέρες</availability>';
			$xml .= '<manufacturer><![CDATA[' . wp_get_post_terms( $id, 'product_brand' )[0]->name . ']]></manufacturer>';
			$xml .= '</product>';
		}
		$xml .= '</products></webstore>';
		return $xml;
	}
}
