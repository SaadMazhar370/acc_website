<?php
/**
 * Template for displaying lead info
 *
 * @author        Themeum
 * @url https://themeum.com
 * @package       TutorLMS/Templates
 * @since         1.0.0
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

global $post, $authordata;
global $edumall_course;

$profile_url = tutor_utils()->profile_url( $authordata->ID, true );
$show_author = tutor_utils()->get_option( 'enable_course_author' );
?>

<div class="tutor-single-course-lead-info">

	<div class="tutor-course-badges-wrap">

		<?php
		$price_badge = Edumall_Tutor::instance()->get_course_price_badge_text();
		if ( ! empty( $price_badge ) ) {
			echo '<div class="tutor-course-badges"><div class="tutor-course-badge onsale">' . $price_badge . '</div></div>';
		}
		?>

		<div class="tutor-course-header-categories">
			<?php
			$course_categories = get_tutor_course_categories();
			if ( is_array( $course_categories ) && count( $course_categories ) ) {
				?>
				<?php
				foreach ( $course_categories as $course_category ) {
					$category_name = $course_category->name;
					$category_link = get_term_link( $course_category->term_id );
					echo "<a href='$category_link'>$category_name</a>";
				}
				?>
			<?php } ?>
		</div>
	</div>

	<?php do_action( 'tutor_course/single/title/before' ); ?>

	<h1 class="tutor-course-header-h1"><?php the_title(); ?></h1>

	<?php do_action( 'tutor_course/single/title/after' ); ?>

	<?php do_action( 'tutor_course/single/lead_meta/before' ); ?>

	<div class="tutor-single-course-lead-meta">
		<?php if ( $show_author ) : ?>
			<div class="lead-meta-item meta-instructor">
				<div class="lead-meta-label">
					<a href="<?php echo esc_url( $profile_url ); ?>">
						<?php
						echo wp_kses(
							tutor_utils()->get_tutor_avatar( get_the_author_meta( 'ID' ) ),
							tutor_utils()->allowed_avatar_tags()
						);
						?>
					</a>
				</div>
				<div class="lead-meta-value instructor-name">
					<a href="<?php echo esc_url( $profile_url ); ?>"><?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?></a>
				</div>

			</div>
		<?php endif; ?>

		<?php
		if ( get_tutor_option( 'enable_course_update_date' ) ) { ?>
			<div class="lead-meta-item meta-last-update">
				<span
					class="lead-meta-value"><?php echo esc_html__( 'Last Update', 'edumall' ) . ' ' . get_the_modified_date(); ?></span>
			</div>
		<?php } ?>
	</div>

	<div class="tutor-single-course-lead-meta">
		<?php if ( get_tutor_option( 'enable_course_review' ) ) : ?>
			<?php
			$course_rating = $edumall_course->get_rating();
			$rating_count  = intval( $course_rating->rating_count );
			?>
			<?php if ( $rating_count > 0 ) : ?>
				<div class="lead-meta-item meta-course-rating">
					<div class="tutor-single-course-rating">
						<div
							class="course-rating-average heading-color"><?php echo '<span>' . Edumall_Helper::number_format_nice_float( $course_rating->rating_avg ) . '</span> /5'; ?></div>
						<?php Edumall_Templates::render_rating( $course_rating->rating_avg ); ?>
						<?php echo '<div class="rating-count">(' . $course_rating->rating_count . ')</div>'; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( get_tutor_option( 'enable_course_total_enrolled' ) ) : ?>
			<div class="lead-meta-item meta-course-total-enrolled">
				<div class="lead-meta-label">
					<i class="meta-icon fa-regular fa-user-alt"></i>
				</div>
				<div
					class="lead-meta-value student-enrolled"><?php echo sprintf( esc_html__( '%s already enrolled', 'edumall' ), $edumall_course->get_enrolled_users_count() ); ?></div>
			</div>
		<?php endif; ?>
	</div>

	<?php do_action( 'tutor_course/single/lead_meta/after' ); ?>
</div>
