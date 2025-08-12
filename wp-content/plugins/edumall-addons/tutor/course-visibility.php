<?php

namespace Edumall_Addons\Tutor;

defined( 'ABSPATH' ) || exit;

class Course_Visibility {

	protected static $instance = null;

	const TAXONOMY_NAME = 'course-visibility';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		/**
		 * Priority 1 to make save_post action working properly.
		 */
		add_action( 'init', [ $this, 'register_taxonomy' ], 1 );

		add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
		add_action( 'save_post', [ $this, 'course_save_featured_meta' ] );
	}

	public function register_taxonomy() {
		$course_post_type = tutor()->course_post_type;

		$args = array(
			'hierarchical'      => false,
			'show_ui'           => false,
			'show_in_nav_menus' => false,
			'query_var'         => is_admin(),
			'rewrite'           => false,
			'public'            => false,
		);

		register_taxonomy( self::TAXONOMY_NAME, $course_post_type, $args );
	}

	public function add_meta_box() {
		if ( current_user_can( 'administrator' ) ) {
			add_meta_box(
				'course_featured_toggle',
				__( 'Featured Course', 'edumall-addons' ),
				[ $this, 'course_featured_toggle_callback' ],
				tutor()->course_post_type,
				'side',
				'default'
			);
		}
	}

	public function course_featured_toggle_callback( $post ) {
		$course_id     = $post->ID;
		$option_is_set = false;

		if ( has_term( 'featured', self::TAXONOMY_NAME, $course_id ) ) {
			$option_is_set = true;
		}

		wp_nonce_field( 'course_featured_nonce_action', 'course_featured_nonce' );
		?>
		<p>
			<label style="display: flex; align-items: center; gap: 8px;" for="_course_featured">
				<input type="checkbox" name="_course_featured" value="1" <?php checked( $option_is_set ); ?> id="_course_featured"/>
				<span><?php esc_html_e( 'Mark as featured course', 'edumall-addons' ); ?></span>
			</label>
		</p>
		<?php
	}

	public function course_save_featured_meta( $post_id ) {
		if ( ! isset( $_POST['course_featured_nonce'] ) ||
		     ! wp_verify_nonce( $_POST['course_featured_nonce'], 'course_featured_nonce_action' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Only administrator role can set course as featured.
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}

		$is_featured = isset( $_POST['_course_featured'] ) && '1' === $_POST['_course_featured'];

		if ( $is_featured ) {
			wp_set_post_terms( $post_id, array( 'featured' ), self::TAXONOMY_NAME, true );
		} else {
			$tags           = wp_get_post_terms( $post_id, self::TAXONOMY_NAME );
			$tags_to_delete = array( 'featured' );
			$tags_to_keep   = array();
			foreach ( $tags as $t ) {
				if ( ! in_array( $t->name, $tags_to_delete ) ) {
					$tags_to_keep[] = $t->name;
				}
			}

			wp_set_post_terms( $post_id, $tags_to_keep, self::TAXONOMY_NAME, false );
		}
	}
}

Course_Visibility::instance()->initialize();
