<?php
/**
 * Display single course add to cart
 *
 * @author        themeum
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

$price = apply_filters( 'get_tutor_course_price', null, get_the_ID() );

$is_logged_in             = is_user_logged_in();
$monetize_by              = tutils()->get_option( 'monetize_by' );
$enable_guest_course_cart = tutor_utils()->get_option( 'enable_guest_course_cart' );

$is_public      = get_post_meta( get_the_ID(), '_tutor_is_public_course', true ) == 'yes';
$is_purchasable = tutor_utils()->is_course_purchasable();

$required_logged_in_class = '';
if ( ! $is_logged_in && ! $is_public ) {
	$required_logged_in_class = apply_filters( 'tutor_enroll_required_login_class', 'tutor-open-login-modal' );
}
if ( $is_purchasable && $monetize_by === 'wc' && $enable_guest_course_cart ) {
	$required_logged_in_class = '';
}

$tutor_form_class = apply_filters( 'tutor_enroll_form_classes', [ 'tutor-enroll-form' ] );

$tutor_course_sell_by = apply_filters( 'tutor_course_sell_by', null );

do_action( 'tutor_course/single/add-to-cart/before' );
?>

<div class="tutor-single-add-to-cart-box <?php echo $required_logged_in_class; ?>">
	<?php
	if ( $is_public ) :
		// Get the first content url.
		$first_lesson_url = tutor_utils()->get_course_first_lesson( get_the_ID(), tutor()->lesson_post_type );
		! $first_lesson_url ? $first_lesson_url = tutor_utils()->get_course_first_lesson( get_the_ID() ) : 0;

		ob_start();
		?>
		<div class="<?php echo implode( ' ', $tutor_form_class ); ?> ">
			<div class="tutor-course-enroll-wrap">
				<?php
				Edumall_Templates::render_button( [
					'link'        => [
						'url' => esc_url( $first_lesson_url ),
					],
					'text'        => esc_html__( 'Start Learning', 'edumall' ),
					'extra_class' => 'tutor-btn-enroll tutor-btn tutor-course-purchase-btn',
				] );
				?>
			</div>
		</div>
		<?php echo apply_filters( 'tutor/course/single/entry-box/is_public', ob_get_clean(), get_the_ID() ); //phpcs:ignore
		?>
	<?php elseif ( $is_purchasable && $price && $tutor_course_sell_by ) :
		// Load template based on monetization option.
		ob_start();
		tutor_load_template( 'single.course.add-to-cart-' . $tutor_course_sell_by );
		echo apply_filters( 'tutor/course/single/entry-box/purchasable', ob_get_clean(), get_the_ID() );//phpcs:ignore
	else:
		ob_start();
		?>
		<div class="tutor-course-single-pricing">
			<span class="tutor-fs-4 tutor-fw-bold tutor-color-black">
				<?php esc_html_e( 'Free', 'edumall' ); ?>
			</span>
		</div>
		<form class="<?php echo esc_attr( implode( ' ', $tutor_form_class ) ); ?>" method="post">
			<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
			<input type="hidden" name="tutor_course_id" value="<?php echo get_the_ID(); ?>">
			<input type="hidden" name="tutor_course_action" value="_tutor_course_enroll_now">

			<div class=" tutor-course-enroll-wrap">
				<button type="submit" class="tutor-btn-enroll tutor-btn tutor-course-purchase-btn">
					<?php esc_html_e( 'Enroll Now', 'edumall' ); ?>
				</button>
			</div>
		</form>
		<?php echo apply_filters( 'tutor/course/single/entry-box/free', ob_get_clean(), get_the_ID() ); //phpcs:ignore
		?>
	<?php endif; ?>
</div>

<?php do_action( 'tutor_course/single/add-to-cart/after' ); ?>
