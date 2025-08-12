<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Edumall_Single_Course' ) ) {
	class Edumall_Single_Course {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'body_class', [ $this, 'body_class' ] );

			add_filter( 'edumall/tutor_course/contents/lesson/title', [
				$this,
				'mark_lesson_title_preview',
			], 10, 2 );

			add_action( 'tutor_save_course_after', [ $this, 'sync_course_duration' ], 10, 2 );

			/**
			 * This hook only working with Classic Editor.
			 */
			add_filter( 'wp_dropdown_users_args', [ $this, 'add_instructor_to_author_dropdown' ], 10, 2 );

			add_filter( 'edumall_title_bar_type', [ $this, 'update_title_bar' ], PHP_INT_MAX );

			add_action( 'wp_footer', [ $this, 'output_login_modal' ] );
		}

		public function body_class( $classes ) {
			if ( Edumall_Tutor::instance()->is_single_course() ) {
				$style     = Edumall::setting( 'single_course_layout' );
				$classes[] = "single-course-{$style}";

				$course_id                         = get_the_ID();
				$is_public                         = \TUTOR\Course_List::is_public( $course_id );
				$student_must_login_to_view_course = tutor_utils()->get_option( 'student_must_login_to_view_course' );

				if ( ! is_user_logged_in() && ! $is_public && $student_must_login_to_view_course ) {
					$classes[] = 'login-require';
				}
			}

			return $classes;
		}

		public function output_login_modal() {
			if ( Edumall_Tutor::instance()->is_single_course() && ! is_user_logged_in() ) {
				tutor_load_template( 'custom.modal.login' );
			}
		}

		public function update_title_bar( $type ) {
			if ( Edumall_Tutor::instance()->is_single_course() ) {
				$course_id                         = get_the_ID();
				$is_public                         = \TUTOR\Course_List::is_public( $course_id );
				$student_must_login_to_view_course = tutor_utils()->get_option( 'student_must_login_to_view_course' );

				if ( ! is_user_logged_in() && ! $is_public && $student_must_login_to_view_course ) {
					return '05';
				}
			}

			return $type;
		}

		/**
		 * @param $title
		 * @param $post_id
		 *
		 * @return string
		 *
		 * Mark lesson title preview from this method
		 * @see \TUTOR_CP\CoursePreview::mark_lesson_title_preview()
		 */
		public function mark_lesson_title_preview( $title, $post_id ) {
			$is_preview = (bool) get_post_meta( $post_id, '_is_preview', true );
			if ( $is_preview ) {
				$newTitle = '<span class="lesson-preview-title">' . $title . '</span><span class="lesson-preview-icon "><a class="button btn-lesson-preview" href="' . get_the_permalink( $post_id ) . '">' . esc_html__( 'Preview', 'edumall' ) . '</a></span>';
			} else {
				$newTitle = '<span class="lesson-preview-title">' . $title . '</span><span class="lesson-preview-icon"><i class="fa-regular fa-lock-alt"></i></span>';
			}

			return $newTitle;
		}

		/**
		 * Tutor save course duration as array serialize.
		 * So we need add extra meta key of duration to filterable.
		 * Convert duration to seconds.
		 */
		public function sync_course_duration( $post_ID, $post ) {
			$course_duration_seconds = 0;
			$duration                = false;

			if ( ! empty( $_POST['additional_content']['course_duration'] ) ) {
				$duration = $_POST['additional_content']['course_duration'];
			} elseif ( ! empty( [ 'course_duration' ] ) ) {
				$duration = $_POST['course_duration']; // Old version fallback.
			}

			// Course Duration.
			if ( ! empty( $duration ) ) {
				$hours = isset( $duration['hours'] ) ? intval( $duration['hours'] ) : 0;
				if ( $hours > 0 ) {
					$course_duration_seconds += $hours * HOUR_IN_SECONDS;
				}

				$minutes = isset( $duration['minutes'] ) ? intval( $duration['minutes'] ) : 0;
				if ( $minutes > 0 ) {
					$course_duration_seconds += $minutes * MINUTE_IN_SECONDS;
				}

				$seconds = isset( $duration['seconds'] ) ? intval( $duration['seconds'] ) : 0; // Fallback old version.
				if ( $seconds > 0 ) {
					$course_duration_seconds += $seconds;
				}
			}

			update_post_meta( $post_ID, '_course_duration_in_seconds', $course_duration_seconds );
		}

		public function add_instructor_to_author_dropdown( $query_args, $r ) {
			$screen = get_current_screen();

			$user  = wp_get_current_user();
			$roles = ( array ) $user->roles;

			/**
			 * Only allows administrator or editor assign course author.
			 */
			$allowed_roles = [ 'administrator', 'editor' ];

			if ( $screen->post_type == 'courses' && count( array_intersect( $roles, $allowed_roles ) ) > 0 ):
				// Add instructors.
				$query_args['role__in'] = array_merge( $query_args['role__in'], array( tutor()->instructor_role ) );

				// Unset default role
				unset( $query_args['who'] );
			endif;

			return $query_args;
		}
	}
}
