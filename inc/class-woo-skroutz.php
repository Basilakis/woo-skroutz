<?php

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
		add_action( 'init', array( $this, 'print_xml' ) );
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
			echo $this->get_xml();
			exit();
		}
	}

	/**
	 * Get meta.
	 * 
	 * @access public
	 * @since 1.0
	 * @param $name The meta-name.
	 * @return mixed The post-meta value.
	 */
	public function get_meta( $value ) {
		global $post;

		$field = get_post_meta( $post->ID, $value, true );
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
	function add_meta_box() {
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
	 * The metabox HTML.
	 *
	 * @access public
	 * @since 1.0
	 * @param WP_Post The WP_Post object.
	 * @return void
	 */
	function html( $post) {
		wp_nonce_field( '_skroutz_nonce', 'skroutz_nonce' );
		$categories = array(
			'cat1' => 'Category 1',
			'cat2' => 'Category 2',
		);
		$availabilities = array(
			''          => 'Αυτόματο',
			'immediate' => 'Άμεση παραλαβή / Παράδoση 1 έως 3 ημέρες',
			'1to3'      => 'Παράδοση σε 1 - 3 ημέρες',
			'4to10'     => 'Παράδοση σε 4 - 10 ημέρες',
			'orderonly' => 'Κατόπιν Παραγγελίας',
		);
		?>

		<p><?php esc_attr_e( 'Skroutz Product Details', 'skroutz' ); ?></p>
		<p>
			<input type="checkbox" name="skroutz_add_to_skroutz" id="skroutz_add_to_skroutz" value="add-to-skroutz" <?php echo ( $this->get_meta( 'skroutz_add_to_skroutz' ) === 'add-to-skroutz' ) ? 'checked' : ''; ?>>
			<label for="skroutz_add_to_skroutz"><?php _e( 'Add to skroutz', 'skroutz' ); ?></label>
		</p>
		<p>
			<label for="skroutz_category"><?php _e( 'Category', 'skroutz' ); ?></label><br>
			<select name="skroutz_category" id="skroutz_category">
				<?php foreach ( $categories as $slug => $label ) : ?>
					<option <?php echo ( $slug === $this->get_meta( 'skroutz_category' ) ) ? 'selected' : '' ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="skroutz_shipping_cost"><?php _e( 'Shipping Cost', 'skroutz' ); ?></label><br>
			<input type="number" min="0" max="100" step="0.1" name="skroutz_shipping_cost" id="skroutz_shipping_cost" value="<?php echo $this->get_meta( 'skroutz_shipping_cost' ); ?>">
		</p>
		<p>
			<label for="skroutz_description"><?php _e( 'Description', 'skroutz' ); ?></label><br>
			<textarea name="skroutz_description" id="skroutz_description" ><?php echo $this->get_meta( 'skroutz_description' ); ?></textarea>
		</p>
		<p>
			<label for="skroutz_availability"><?php _e( 'availability', 'skroutz' ); ?></label><br>
			<select name="skroutz_availability" id="skroutz_availability">
				<?php foreach ( $availabilities as $key => $val ) : ?>
					<option <?php echo ( $key === $this->get_meta( 'skroutz_availability' ) ) ? 'selected' : '' ?>><?php echo $val; // WPCS: CSS ok. ?></option>
				<?php endforeach; ?>
			</select>
		</p>
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
	function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['skroutz_nonce'] ) || ! wp_verify_nonce( $_POST['skroutz_nonce'], '_skroutz_nonce' ) ) {
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