<?php
/**
 * Template for displaying student Public Profile
 *
 * @author        Themeum
 * @url https://themeum.com
 * @package       TutorLMS/Templates
 * @since         1.0.0
 * @version       1.4.3
 *
 * @theme-since   3.1.0
 * @theme-version 3.1.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

use TUTOR\Input;
use Tutor\Models\CourseModel;

// Get the accessed user data.
$user_name = sanitize_text_field( get_query_var( 'tutor_profile_username' ) );
$get_user  = tutor_utils()->get_user_by_login( $user_name );

// Show not found page if user not exists.
if ( ! is_object( $get_user ) || ! property_exists( $get_user, 'ID' ) ) {
	wp_safe_redirect( get_home_url() . '/404' );
	exit;
}

// Prepare meta data to render the page based on user and view type.
$user_id        = $get_user->ID;
$is_instructor  = Input::has( 'view' ) ? 'instructor' === Input::get( 'view' ) : tutor_utils()->is_instructor( $user_id, true );
$layout_key     = $is_instructor ? 'public_profile_layout' : 'student_public_profile_layout';
$profile_layout = tutor_utils()->get_option( $layout_key, 'private' );
$user_type      = $is_instructor ? 'instructor' : 'student';

if ( 'private' === $profile_layout ) {
	// Disable profile access then.
	wp_safe_redirect( get_home_url() );
	exit;
}

$sub_page  = sanitize_text_field( get_query_var( 'profile_sub_page' ) );

if ( empty( $get_user ) ) {
	return;
}

$user_id = $get_user->ID;

global $wp_query;

$profile_sub_page = '';
if ( isset( $wp_query->query_vars['profile_sub_page'] ) && $wp_query->query_vars['profile_sub_page'] ) {
	$profile_sub_page = $wp_query->query_vars['profile_sub_page'];
}
?>
	<div class="page-content">
		<div class="photo-area">
			<div class="cover-area">
				<div style="background-image:url(<?php echo esc_url( tutor_utils()->get_cover_photo_url( $user_id ) ); ?>)"></div>
			</div>
		</div>

		<div class="tutor-dashboard-header-wrap">
			<div class="container small-gutter">
				<div class="row">
					<div class="col-md-12">
						<div class="tutor-dashboard-header">
							<div class="dashboard-header-toggle-menu dashboard-header-menu-open-btn">
								<?php echo Edumall_Helper::get_file_contents( EDUMALL_THEME_SVG_DIR . '/icon-toggle-sidebar.svg' ); ?>
							</div>
							<div class="tutor-header-user-info">
								<div class="tutor-dashboard-header-avatar">
									<?php echo edumall_get_avatar( $user_id, 150 ); ?>
								</div>
								<div class="tutor-dashboard-header-info">
									<h4 class="tutor-dashboard-header-display-name">
										<span
											class="welcome-text"><?php esc_html_e( 'Howdy, I\'m', 'edumall' ); ?></span>
										<?php echo esc_html( $get_user->display_name ); ?>
									</h4>

									<?php if ( user_can( $user_id, tutor()->instructor_role ) ) : ?>
										<?php
										$instructor_rating       = tutils()->get_instructor_ratings( $get_user->ID );
										$instructor_rating_count = sprintf(
											_n( '%s rating', '%s ratings', $instructor_rating->rating_count, 'edumall' ),
											number_format_i18n( $instructor_rating->rating_count )
										);
										?>
										<div class="tutor-dashboard-header-stats">
											<div class="tutor-dashboard-header-ratings">
												<?php Edumall_Templates::render_rating( $instructor_rating->rating_avg ) ?>
												<span
													class="rating-average"><?php echo esc_html( $instructor_rating->rating_avg ); ?></span>
												<span class="rating-count">
												<?php echo '(' . esc_html( $instructor_rating_count ) . ')'; ?>
											</span>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>

							<?php
							$tutor_user_social_icons = tutor_utils()->tutor_user_social_icons();
							?>
							<?php if ( count( $tutor_user_social_icons ) ) : ?>
								<?php
								ob_start();
								foreach ( $tutor_user_social_icons as $key => $social_icon ) {
									$icon_url = get_user_meta( $user_id, $key, true );
									if ( $icon_url ) {
										echo "<a href='" . esc_url( $icon_url ) . "' target='_blank' class='" . $social_icon['icon_classes'] . "'></a>";
									}
								}
								$social_html = ob_get_clean();
								?>
								<?php if ( ! empty( $social_html ) ) : ?>
									<div class="tutor-dashboard-social-icons">
										<h4 class="social-icons-text-help"><?php esc_html_e( 'Follow me', 'edumall' ); ?></h4>
										<?php echo '' . $social_html; ?>
									</div>
								<?php endif; ?>
							<?php endif; ?>

							<?php Edumall_Header::instance()->print_open_canvas_menu_button(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container small-gutter">
			<div class="row">
				<div class="page-main-content">
					<div class="tutor-dashboard-content">
						<?php
						//phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
						do_action( 'tutor_profile/' . $user_type . '/before/wrap' );
						?>

						<?php
						if ( $sub_page ) {
							tutor_load_template( 'profile.' . $sub_page, compact( 'get_user' ) );
						} else {
							tutor_load_template( 'profile.bio', compact( 'get_user' ) );
						}
						?>

						<?php
						if ( $is_instructor ) {
							?>
							<?php
							add_filter(
								'courses_col_per_row',
								function() {
									return 3;
								}
							);

							tutor_load_template( 'profile.courses_taken' );
							?>
							<?php
						}
						?>

						<?php
						//phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
						do_action( 'tutor_profile/' . $user_type . '/after/wrap' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
